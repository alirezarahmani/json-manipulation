<?php

declare(strict_types=1);

namespace Boot;

use ArrayObject;
use Assert\Assertion;
use Exception;
use InvalidArgumentException;
use JetBrains\PhpStorm\ArrayShape;
use ReflectionClass;
use Supermetrics\Infrastructure\MysqlPostRepository;
use Supermetrics\Service\PostManager;
use Supermetrics\Service\SyncPosts;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Yaml\Yaml;

/**
 * Class Supermetrics
 * @package Boot
 */
class Supermetrics
{
    const CONSOLE_APPLICATION = 'console_application';

    /**
     * @var Container
     */
    private static Container $containerBuilder;

    /**
     * Start constructor.
     * @param Container $containerBuilder
     */
    private function __construct(Container $containerBuilder)
    {
        self::$containerBuilder = $containerBuilder;
    }

    /**
     * @throws Exception
     */
    public function explode(): void
    {
        if (PHP_SAPI == "cli") {
            $this->runCli(new ArgvInput(), new ConsoleOutput());
        } else {
            $this->runHttp(Request::createFromGlobals());
        }
    }

    /**
     * @param Request $request
     */
    private function runHttp(Request $request)
    {
        self::$containerBuilder->set('_request', $request);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws Exception
     */
    private function runCli(InputInterface $input, OutputInterface $output): void
    {
        $application = new Application();
        $application->setAutoExit(false);
        $container = self::$containerBuilder;
        $application->setCatchExceptions(false);
        foreach ($container->get(self::CONSOLE_APPLICATION)['classes'] as $class) {
            $application->add(new $class($container));
        }
        try {
            $application->run($input, $output);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    #[ArrayShape(['classes' => "array"])] private static function loadConsoleApplications(string $appPath): array
    {
        $classes = [];
        $dir = $appPath . '/src/Cli';
        if (!is_dir($dir)) {
            throw new InvalidArgumentException('no valid directory');
        }
        $finder = new Finder();

        foreach ($finder->files()->name('*Cli.php')->in($dir) as $file) {
            /**
             * @var SplFileInfo $file
             */
            $className = 'Supermetrics\\Cli\\'.substr($file->getRelativePathname(), 0, -4);
            $reflection = new ReflectionClass($className);
            if ($reflection->isInstantiable()) {
                $classes[] = $className;
            }
        }
        return ['classes' => $classes];
    }

    /**
     * @return static
     * @throws \Assert\AssertionFailedException
     */
    public static function create(): self
    {
        $compiledClassName = 'MyCachedContainer';
        $cacheDir = __DIR__ . '/../cache/';
        $cachedContainerFile = "{$cacheDir}container.php";

        //create container if not exist
        if (!is_file($cachedContainerFile)) {
            $configFile = __DIR__ . '/../config/setting.yml';
            Assertion::file($configFile, ' the ' . $configFile . ' found.');
            $config = Yaml::parse(file_get_contents($configFile));
            $container = new ContainerBuilder(new ParameterBag());
            $container->register(MysqlPostRepository::class)
                ->addArgument($config['mysql']['uri'])
                ->addArgument($config['mysql']['user'])
                ->addArgument($config['mysql']['pass'])
                ->addArgument($config['mysql']['db'])
                ->setPublic(true);
            $container->register(PostManager::class)
                ->addArgument(new Reference(MysqlPostRepository::class))
                ->setPublic(true);
            $container->register(SyncPosts::class)
                ->addArgument(new Reference(PostManager::class))
                ->setPublic(true);
            $container->compile();
            file_put_contents($cachedContainerFile, (new PhpDumper($container))->dump(['class' => $compiledClassName]));
        }

        /** @noinspection PhpIncludeInspection */
        include_once $cachedContainerFile;

        $container =  new $compiledClassName();
        $container->set(self::CONSOLE_APPLICATION, new ArrayObject(self::loadConsoleApplications(__DIR__ . '/../')));
        $request = Request::createFromGlobals();
        $container->set(Request::class, $request);
        return new self($container);
    }

    /**
     * @return Container
     */
    public static function getContainer(): Container
    {
        return self::$containerBuilder;
    }
}

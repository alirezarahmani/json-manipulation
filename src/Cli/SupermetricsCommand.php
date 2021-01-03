<?php

declare(strict_types=1);

namespace Supermetrics\Cli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class SupermetricsCommand
 * @package Supermetrics\Cli
 */
abstract class SupermetricsCommand extends Command
{
    /**
     * SupermetricsCommand constructor.
     * @param Container $container
     */
    public function __construct(protected Container $container)
    {
        parent::__construct();
        $this->addOption('force');
    }
}

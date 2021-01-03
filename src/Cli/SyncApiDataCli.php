<?php

declare(strict_types=1);

namespace Supermetrics\Cli;

use Supermetrics\Infrastructure\SupermetricsPostsApiClient;
use Supermetrics\Service\SyncPosts;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ReportSyncCli
 * @package Supermetrics\Cli
 */
class SyncApiDataCli extends SupermetricsCommand
{
    /**
     * configure
     */
    protected function configure()
    {
        $this
            ->setName('supermetrics:sync:api')
            ->setDescription('sync Supermetrics api data to database');
    }

    /**
     * Run eventual background task
     * to populate database from Api Client
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Exception
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var SyncPosts $syncPosts */
        $syncPosts = $this->container->get(SyncPosts::class);
        $output->writeln('start Update');
        $syncPosts->apiClient(new SupermetricsPostsApiClient());
        $output->writeln('successfully done');
        return 1;
    }
}

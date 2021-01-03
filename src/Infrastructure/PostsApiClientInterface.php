<?php

declare(strict_types=1);

namespace Supermetrics\Infrastructure;

/**
 * Interface ApiClientInterface
 * @package Supermetrics\Infrastructure
 */
interface PostsApiClientInterface
{
    /**
     * @param int $page
     * @return array
     */
    public function getResults($page = 0): array;

    /**
     * get token
     */
    public function refreshToken(): void;

    /**
     * @return int
     */
    public function getCurrentPage(): int;
}

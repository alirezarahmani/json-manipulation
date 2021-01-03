<?php

declare(strict_types=1);

namespace Supermetrics\Service;

use Supermetrics\Domain\Post;
use Supermetrics\Domain\PostsRepositoryInterface;
use DateTime;

/**
 * Class PostManager
 * @package Supermetrics\Service
 */
class PostManager
{
    private PostsRepositoryInterface $repository;

    public function __construct(PostsRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param string $id
     * @param string $fromName
     * @param string $fromId
     * @param string $message
     * @param string $type
     * @param DateTime $createdTime
     */
    public function createFromScratch(
        string $id,
        string $fromName,
        string $fromId,
        string $message,
        string $type,
        DateTime $createdTime
    ) {
        # run events before create
        $domain = new Post($id, $fromName, $fromId, $message, $type, $createdTime);
        # run events after
        $this->repository->save($domain);
    }

    /**
     * @return array
     */
    public function getReportResult(): array
    {
        return ['monthly' => $this->repository->findReportPerMonth(), 'weekly' => $this->repository->findReportWeeks()];
    }
}

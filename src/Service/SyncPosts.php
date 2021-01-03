<?php

declare(strict_types=1);

namespace Supermetrics\Service;

use DateTime;
use Supermetrics\Infrastructure\Exceptions\ApiClientException;
use Supermetrics\Infrastructure\PostsApiClientInterface;

/**
 * Class SyncPosts
 * @package Supermetrics\Service
 */
class SyncPosts
{
    /**
     * @var PostManager
     */
    private PostManager $manager;

    /**
     * SyncPosts constructor.
     * @param PostManager $manager
     */
    public function __construct(PostManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param PostsApiClientInterface $apiClient
     * @throws \Exception
     */
    public function apiClient(PostsApiClientInterface $apiClient)
    {
        $page = 1;
        $tries = 0;
        
        while (true) {
            try {
                $data = $apiClient->getResults($page);
                # sent page with return page in api are different
                # it means that there is no more pages
                if ($page++ != $apiClient->getCurrentPage()) {
                    break;
                }
                foreach ($data as $params) {
                    $params['created_time'] = new DateTime($params['created_time']);
                    $this->manager->createFromScratch(
                        $params['id'],
                        $params['from_name'],
                        $params['from_id'],
                        $params['message'],
                        $params['type'],
                        $params['created_time']
                    );
                }
            } catch (ApiClientException $apiClientException) {
                if ($apiClientException->getCode() === 404 && $tries  < 4) {
                    $tries++;
                    $apiClient->refreshToken();
                    continue;
                }
            }
        }
    }
}

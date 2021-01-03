<?php

namespace Supermetrics\Http\Apiv1;

use Assert\InvalidArgumentException;
use Boot\Supermetrics;
use Supermetrics\Requests\ApiJsonResponse;
use Supermetrics\Requests\ApiRequest;
use Supermetrics\Service\PostManager;

class PostsController
{
    /**
     * @param ApiRequest $apiRequest
     * @param ApiJsonResponse $apiJsonResponse
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Assert\AssertionFailedException
     * @throws \Exception
     */
    public function indexAction(ApiRequest $apiRequest, ApiJsonResponse $apiJsonResponse)
    {
        $container = Supermetrics::getContainer();
        /** @var PostManager $manager */
        $manager = $container->get(PostManager::class);
        try {
            return $apiJsonResponse->success($manager->getReportResult());
        } catch (InvalidArgumentException $exception) {
            return $apiJsonResponse->error([$exception->getMessage()]);
        }
    }
}

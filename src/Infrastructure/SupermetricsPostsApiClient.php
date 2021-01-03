<?php

declare(strict_types=1);

namespace Supermetrics\Infrastructure;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Supermetrics\Infrastructure\Exceptions\ApiClientException;

/**
 * Class RestApiDataSource
 * @package Supermetrics\Infrastructure
 */
class SupermetricsPostsApiClient implements PostsApiClientInterface
{
    private const BASE_URL = 'https://api.supermetrics.com/assignment/';
    private const CLIENT_ID = 'ju16a6m81mhid5ue1z3v2g0uh';

    /**
     * @var Client
     */
    private Client $client;
    /**
     * @var mixed
     */
    private int $currentPage = 1;

    public function __construct(private string $token = '')
    {
        $this->client = new Client();
        $this->refreshToken();
    }

    /**
     * @param int $page
     * @return array
     * @throws GuzzleException
     */
    public function getResults($page = 1): array
    {
        try {
            $request = $this->client->get(
                self::BASE_URL . 'posts',
                [
                    'query' => [
                        'sl_token' => $this->token,
                        'page' => $page
                    ]
                ]
            );
            $response = $request->getBody()->getContents();
            $response = json_decode($response, true);
            $this->currentPage = $response['data']['page'];
            return $response['data']['posts'];
        } catch (GuzzleException $e) {
            throw new ApiClientException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @throws GuzzleException
     */
    public function refreshToken(): void
    {
        try {
            $request = $this->client->post(
                self::BASE_URL . 'register',
                [
                    'form_params' => [
                        'client_id' => self::CLIENT_ID,
                        'name' => 'Alireza',
                        'email' => 'alirezarahmani@live.com'
                    ]
                ]
            );
            $response = $request->getBody()->getContents();
            $response = json_decode($response, true);
            $this->token = $response['data']['sl_token'];
        } catch (GuzzleException $e) {
            throw new ApiClientException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }
}

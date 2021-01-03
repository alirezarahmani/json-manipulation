<?php

namespace Boot;

use Supermetrics\Requests\ApiJsonResponse;
use Supermetrics\Requests\ApiRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class Router
 * @package Boot
 */
class Router
{
    //@todo: create a routerSubscriber and remove this
    //@todo: create a dependency resolver for controller arguments
    public static function routes()
    {
        try {
            /** @var Request $request */
            $request = Supermetrics::getContainer()->get(Request::class);
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
            $apiRequest = new ApiRequest($request);
            $apiResponse = new ApiJsonResponse();

            $context = new RequestContext('/');
            $context->fromRequest($request);
            $matcher = new UrlMatcher(self::initRoutes(), $context);
            if ($parameters = $matcher->match($request->getPathInfo())) {
                $controller = new $parameters['_controller'];
                return call_user_func(
                    [$controller, $parameters['_method']],
                    $apiRequest,
                    $apiResponse
                );
            }
        } catch (ResourceNotFoundException | MethodNotAllowedException $e) {
            return (new JsonResponse(['sorry requested page not found'], 404))->send();
        }
        return 0;
    }

    private static function initRoutes(): RouteCollection
    {
        $indexRoute = new Route(
            '/posts',
            ['_controller' => 'Supermetrics\\Http\\Apiv1\\PostsController', '_method' => 'indexAction'],
            [],
            [],
            '',
            [],
            'GET'
        );
        $routes = new RouteCollection();
        $routes->add('poss_index', $indexRoute);
        return $routes;
    }
}

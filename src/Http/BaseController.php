<?php

namespace Supermetrics\Http;

use Boot\Supermetrics;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class BaseController
 * @package Supermetrics\Http
 */
abstract class BaseController
{
    /**
     * @return Container
     */
    #[Pure] public function getContainer(): Container
    {
        return Supermetrics::getContainer();
    }
}

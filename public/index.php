<?php
require '../vendor/autoload.php';

use Boot\Supermetrics;
use Boot\Router;

Supermetrics::create()->explode();
Router::routes();

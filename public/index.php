<?php

use Phastebin\Phastebin;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$phastebin = new Phastebin($app);

$app->run();

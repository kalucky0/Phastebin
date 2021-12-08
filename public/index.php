<?php

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	$loader = require '../vendor/autoload.php';
	$config = require '../config/config.php';

	$app = new \Slim\App($config);
	$phastebin = new \Phastebin\Phastebin($app);

	$app->run();

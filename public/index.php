<?php

declare(strict_types=1);

use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;
use Slim\ResponseEmitter;

require dirname(__DIR__) . '/config/bootstrap.php';

AppFactory::setContainer($container);
$app              = AppFactory::create();
$callableResolver = $app->getCallableResolver();

$routes = require dirname(__DIR__) . '/config/routes.php';
$routes($app);

$serverRequestCreator = ServerRequestCreatorFactory::create();
$request              = $serverRequestCreator->createServerRequestFromGlobals();

$response        = $app->handle($request);
$responseEmitter = new ResponseEmitter();
$responseEmitter->emit($response);

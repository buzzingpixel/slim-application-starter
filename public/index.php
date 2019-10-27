<?php

declare(strict_types=1);

use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;
use Slim\ResponseEmitter;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run as WhoopsRun;

require dirname(__DIR__) . '/config/bootstrap.php';

if (class_exists(WhoopsRun::class)) {
    $whoops = new WhoopsRun();
    $whoops->prependHandler(new PrettyPageHandler());
    $whoops->register();
}

AppFactory::setContainer($container);
$app = AppFactory::create();

$callableResolver = $app->getCallableResolver();

$routes = require dirname(__DIR__) . '/config/routes.php';
$routes($app);

$request = ServerRequestCreatorFactory::create()->createServerRequestFromGlobals();

// TODO: Handle errors and 404s

$responseEmitter = new ResponseEmitter();
$responseEmitter->emit($app->handle($request));

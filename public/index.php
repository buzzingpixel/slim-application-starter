<?php

declare(strict_types=1);

use App\Http\Error\HttpErrorAction;
use Psr\Container\ContainerInterface;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;
use Slim\ResponseEmitter;
use Whoops\Run as WhoopsRun;

$bootstrap = require dirname(__DIR__) . '/config/bootstrap.php';

/** @var ContainerInterface $container */
$container = $bootstrap();

AppFactory::setContainer($container);
$app = AppFactory::create();

$callableResolver = $app->getCallableResolver();

$routes = require dirname(__DIR__) . '/config/routes.php';
$routes($app);

$request = ServerRequestCreatorFactory::create()->createServerRequestFromGlobals();

if (! class_exists(WhoopsRun::class)) {
    $errorMiddleware = $app->addErrorMiddleware(false, false, false);
    $errorMiddleware->setDefaultErrorHandler($container->get(HttpErrorAction::class));
}

$responseEmitter = $container->get(ResponseEmitter::class);
$responseEmitter->emit($app->handle($request));

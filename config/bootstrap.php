<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run as WhoopsRun;

require dirname(__DIR__) . '/vendor/autoload.php';

if (class_exists(WhoopsRun::class)) {
    $whoops = new WhoopsRun();
    $whoops->prependHandler(new PrettyPageHandler());
    $whoops->register();
}

return static function () : ContainerInterface {
    $containerBuilder = (new ContainerBuilder())
        ->useAnnotations(true)
        ->useAutowiring(true)
        ->addDefinitions(require __DIR__ . '/dependencies.php');

    // TODO: Should be set to true in production
    if (false) {
        $containerBuilder->enableCompilation(__DIR__ . '/../var/cache'); // TODO: <-- set this directory properly
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    return $containerBuilder->build();
};

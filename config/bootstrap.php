<?php

declare(strict_types=1);

use DI\ContainerBuilder;

require dirname(__DIR__) . '/vendor/autoload.php';

$containerBuilder = (new ContainerBuilder())
    ->useAnnotations(true)
    ->useAutowiring(true);

// TODO: Should be set to true in production
if (false) {
    $containerBuilder->enableCompilation(__DIR__ . '/../var/cache'); // TODO: <-- set this directory propertly
}

/** @noinspection PhpUnhandledExceptionInspection */
$container = $containerBuilder->build();

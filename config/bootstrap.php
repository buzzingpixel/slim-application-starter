<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use Whoops\Handler\PlainTextHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run as WhoopsRun;

require dirname(__DIR__) . '/vendor/autoload.php';

if (class_exists(WhoopsRun::class)) {
    $whoops = new WhoopsRun();
    $whoops->prependHandler(
        mb_strtolower(PHP_SAPI) === 'cli' ?
            new PlainTextHandler() :
            new PrettyPageHandler()
    );
    $whoops->register();
}

if (class_exists(Symfony\Component\VarDumper\VarDumper::class)) {
    require __DIR__ . '/dumper.php';
}

return static function () : ContainerInterface {
    $containerBuilder = (new ContainerBuilder())
        ->useAnnotations(true)
        ->useAutowiring(true)
        ->ignorePhpDocErrors(true)
        ->addDefinitions(require __DIR__ . '/dependencies.php');

    if (getenv('ENABLE_DI_COMPILATION') === 'true') {
        $containerBuilder->enableCompilation(dirname(__DIR__) . '/storage/di-cache');
        $containerBuilder->writeProxiesToFile(true, dirname(__DIR__) . '/storage/di-cache');
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    return $containerBuilder->build();
};

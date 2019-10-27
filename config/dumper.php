<?php

declare(strict_types=1);

use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Dumper\ContextProvider\CliContextProvider;
use Symfony\Component\VarDumper\Dumper\ContextProvider\SourceContextProvider;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;
use Symfony\Component\VarDumper\Dumper\ServerDumper;
use Symfony\Component\VarDumper\VarDumper;

$cloner = new VarCloner();

$htmlDumper = new HtmlDumper();

$htmlDumper->setTheme('light');

$fallbackDumper = in_array(PHP_SAPI, ['cli', 'phpdbg']) ? new CliDumper() : $htmlDumper;

$dumper = new ServerDumper('tcp://127.0.0.1:9912', $fallbackDumper, [
    'cli' => new CliContextProvider(),
    'source' => new SourceContextProvider(),
]);

$twigDumper = $dumper = new ServerDumper('tcp://127.0.0.1:9912', $fallbackDumper);

$varStore            = new stdClass();
$varStore->hasDumped = false;

VarDumper::setHandler(static function ($var) use ($cloner, $dumper, $twigDumper, $varStore) : void {
    if (PHP_SAPI !== 'cli' && ! $varStore->hasDumped) {
        print '<head><title>Symfony Dumper</title></head><body>';
        $varStore->hasDumped = true;
    }

    $checkForTwigDumperFile = debug_backtrace()[1]['file'] ?? '';

    if (! $checkForTwigDumperFile) {
        $checkForTwigDumperFile = debug_backtrace()[2]['file'] ?? '';
    }

    $checkForTwigDumperArray = explode(DIRECTORY_SEPARATOR, $checkForTwigDumperFile);

    $isTwigDumper = $checkForTwigDumperArray[count($checkForTwigDumperArray) -1] === 'TwigDumper.php';

    if ($isTwigDumper) {
        echo '<div style="background-color: #fff; display: inline-block; margin: 10px; padding: 25px;">';
        echo '<pre style="font-size: 14px; margin-bottom: -10px; margin-left: 6px;">';
        if (is_object($var)) {
            echo get_class($var);
        } else {
            echo gettype($var);
        }
        echo '</pre>';
        $twigDumper->dump($cloner->cloneVar($var));
        echo '</div><br>';

        return;
    }

    $traceItem = debug_backtrace()[2];

    if (PHP_SAPI !== 'cli') {
        print '<pre style="margin-bottom: -16px;">';
    }

    print $traceItem['file'] . ':' . $traceItem['line'] . ': ';

    if (PHP_SAPI !== 'cli') {
        print '</pre>';
    }

    echo '<pre style="font-size: 14px; margin-bottom: -16px; margin-left: 6px;">';
    if (is_object($var)) {
        echo get_class($var);
    } else {
        echo gettype($var);
    }
    echo '</pre>';

    $dumper->dump($cloner->cloneVar($var));
});
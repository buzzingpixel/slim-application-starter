<?php

declare(strict_types=1);

use function Safe\ini_set;

/** @noinspection PhpUnhandledExceptionInspection */
ini_set('display_errors', 'On');
/** @noinspection PhpUnhandledExceptionInspection */
ini_set('html_errors', '0');
error_reporting(-1);

require dirname(__DIR__) . '/vendor/autoload.php';

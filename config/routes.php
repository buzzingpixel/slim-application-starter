<?php

declare(strict_types=1);

use App\Http\Home\GetHomeAction;
use Slim\App;

return static function (App $app) : void {
    $app->get('/', GetHomeAction::class);
};

<?php

declare(strict_types=1);

namespace App\Http\Home;

class GetHomeAction
{
    public function __invoke() : void
    {
        var_dump('here');
        die;
    }
}

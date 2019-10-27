<?php

declare(strict_types=1);

namespace App\Http\Home;

use Psr\Http\Message\ResponseInterface;

class GetHomeAction
{
    /** @var GetHomeResponder */
    private $responder;

    public function __construct(GetHomeResponder $responder)
    {
        $this->responder = $responder;
    }

    public function __invoke() : ResponseInterface
    {
        return ($this->responder)();
    }
}

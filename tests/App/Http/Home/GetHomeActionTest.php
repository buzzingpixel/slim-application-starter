<?php

declare(strict_types=1);

namespace Tests\App\Http\Home;

use App\Http\Home\GetHomeAction;
use App\Http\Home\GetHomeResponder;
use PHPUnit\Framework\TestCase;
use Slim\Psr7\Factory\ResponseFactory;

class GetHomeActionTest extends TestCase
{
    public function test() : void
    {
        $responseFactory = new ResponseFactory();

        $action = new GetHomeAction(
            new GetHomeResponder($responseFactory)
        );

        $response = $action();

        self::assertSame('TODO: Implement Home Page', (string) $response->getBody());
    }
}

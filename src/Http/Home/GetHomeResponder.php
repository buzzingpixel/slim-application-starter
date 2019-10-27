<?php

declare(strict_types=1);

namespace App\Http\Home;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

class GetHomeResponder
{
    /** @var ResponseFactoryInterface */
    private $responseFactory;

    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function __invoke() : ResponseInterface
    {
        // TODO: Implement Home Page
        $response = $this->responseFactory->createResponse();

        $response->getBody()->write('TODO: Implement Home Page');

        return $response;
    }
}

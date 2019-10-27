<?php

declare(strict_types=1);

namespace App\Http\Error;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

class Error404Responder
{
    /** @var ResponseFactoryInterface */
    private $responseFactory;

    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function __invoke() : ResponseInterface
    {
        $response = $this->responseFactory->createResponse(404, 'Page Not Found');

        $response->getBody()->write('Page not found');

        return $response;
    }
}

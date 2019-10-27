<?php

declare(strict_types=1);

namespace App\Http\Error;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

class Error500Responder
{
    /** @var ResponseFactoryInterface */
    private $responseFactory;

    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function __invoke() : ResponseInterface
    {
        $response = $this->responseFactory->createResponse(500, 'An internal server error occurred');

        $response->getBody()->write('An internal server error occurred');

        return $response;
    }
}

<?php
declare(strict_types = 1);

namespace Innmind\HttpTransport;

use Innmind\HttpTransport\Exception\ClientErrorException;
use Innmind\Http\Message\{
    RequestInterface,
    ResponseInterface
};

final class ThrowOnClientErrorTransport implements TransportInterface
{
    private $transport;

    public function __construct(TransportInterface $transport)
    {
        $this->transport = $transport;
    }

    public function fulfill(RequestInterface $request): ResponseInterface
    {
        $response = $this->transport->fulfill($request);

        if ($response->statusCode()->value() % 400 < 100) {
            throw new ClientErrorException($request, $response);
        }

        return $response;
    }
}

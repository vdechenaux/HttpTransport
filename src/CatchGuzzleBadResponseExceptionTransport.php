<?php
declare(strict_types = 1);

namespace Innmind\HttpTransport;

use Innmind\Http\{
    Message\Request,
    Message\Response,
    Translator\Response\Psr7Translator
};
use GuzzleHttp\Exception\BadResponseException;

final class CatchGuzzleBadResponseExceptionTransport implements TransportInterface
{
    private $transport;
    private $translator;

    public function __construct(
        TransportInterface $transport,
        Psr7Translator $translator
    ) {
        $this->transport = $transport;
        $this->translator = $translator;
    }

    public function fulfill(Request $request): Response
    {
        try {
            return $this->transport->fulfill($request);
        } catch (BadResponseException $e) {
            if ($e->hasResponse()) {
                return $this->translator->translate($e->getResponse());
            }

            throw $e;
        }
    }
}

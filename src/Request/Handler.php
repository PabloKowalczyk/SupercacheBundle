<?php

declare(strict_types=1);

namespace PabloK\SupercacheBundle\Request;

use PabloK\SupercacheBundle\Cache\RequestHandler;
use PabloK\SupercacheBundle\Exceptions\SecurityViolationException;
use PabloK\SupercacheBundle\Response\Handler as ResponseHandler;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * @internal
 */
final class Handler
{
    /**
     * @var RequestHandler
     */
    private $requestHandler;

    public function __construct(RequestHandler $requestHandler)
    {
        $this->requestHandler = $requestHandler;
    }

    /**
     * @throws SecurityViolationException Tried to obtain cache entry using unsafe path. Generally it should never occur
     *                                    unless invalid Request is passed.
     */
    public function __invoke(GetResponseEvent $getResponseEvent): void
    {
        $request = $getResponseEvent->getRequest();
        $cacheResponse = $this->requestHandler
            ->retrieveCachedResponse($request);

        if (null !== $cacheResponse) {
            $request->attributes
                ->set(ResponseHandler::RESPONSE_SOURCE_ATTR_NAME, ResponseHandler::RESPONSE_SOURCE_CACHE);

            $getResponseEvent->setResponse($cacheResponse);
        }
    }
}

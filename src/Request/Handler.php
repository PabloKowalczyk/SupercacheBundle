<?php

declare(strict_types=1);

namespace PabloK\SupercacheBundle\Request;

use PabloK\SupercacheBundle\Cache\RequestHandler;
use PabloK\SupercacheBundle\Exceptions\SecurityViolationException;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

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
                ->set('response_source', 'cache');

            $getResponseEvent->setResponse($cacheResponse);
        }
    }
}

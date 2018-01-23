<?php

declare(strict_types=1);

namespace PabloK\SupercacheBundle\Response;

use PabloK\SupercacheBundle\Cache\ResponseHandler;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

/**
 * @internal
 */
final class Handler
{
    public const RESPONSE_SOURCE_ATTR_NAME = 'response_source';
    public const RESPONSE_SOURCE_CACHE = 'cache';

    /**
     * @var ResponseHandler
     */
    private $responseHandler;

    public function __construct(ResponseHandler $responseHandler)
    {
        $this->responseHandler = $responseHandler;
    }

    public function __invoke(FilterResponseEvent $event): void
    {
        $request = $event->getRequest();
        $responseSource = $request->attributes
            ->get(self::RESPONSE_SOURCE_ATTR_NAME);

        if (self::RESPONSE_SOURCE_CACHE === $responseSource) { //Prevents re-caching response from cache
            $event->stopPropagation();

            return;
        }

        if (!$event->isMasterRequest()) {
            return; //Caching should only occur on master requests, see https://github.com/kiler129/SupercacheBundle/issues/10
        }

        $this->responseHandler
            ->cacheResponse($event->getRequest(), $event->getResponse());
    }
}

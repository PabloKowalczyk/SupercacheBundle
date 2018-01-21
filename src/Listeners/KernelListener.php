<?php

declare(strict_types=1);

namespace PabloK\SupercacheBundle\Listeners;

use PabloK\SupercacheBundle\Cache\RequestHandler;
use PabloK\SupercacheBundle\Cache\ResponseHandler;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class KernelListener
{
    /**
     * @var ResponseHandler
     */
    private $responseHandler;

    public function __construct(RequestHandler $requestHandler, ResponseHandler $responseHandler)
    {
        $this->responseHandler = $responseHandler;
    }

    /**
     * This method is executed on kernel.response event.
     *
     * @param FilterResponseEvent $event
     *
     * @see {http://symfony.com/doc/current/components/http_kernel/introduction.html#the-kernel-response-event}
     */
    public function onResponse(FilterResponseEvent $event)
    {
        $request = $event->getRequest();
        $responseSource = $request->attributes
            ->get('response_source');

        if ('cache' === $responseSource) { //Prevents re-caching response from cache
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

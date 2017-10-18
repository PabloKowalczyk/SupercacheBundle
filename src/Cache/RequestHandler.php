<?php

declare(strict_types=1);

namespace PabloK\SupercacheBundle\Cache;

use PabloK\SupercacheBundle\Exceptions\SecurityViolationException;
use PabloK\SupercacheBundle\Factory\ResponseFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RequestHandler
{
    /**
     * @var bool
     */
    private $addStatusHeader;
    /**
     * @var CacheManager
     */
    private $cacheManager;
    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    public function __construct(
        CacheManager $cacheManager,
        ResponseFactory $responseFactory,
        bool $addStatusHeader
    ) {
        $this->cacheManager = $cacheManager;
        $this->addStatusHeader = $addStatusHeader;
        $this->responseFactory = $responseFactory;
    }

    /**
     * Tries to obtain cached response.
     *
     * @param Request $request
     *
     * @return Response|null will return cached content as response or null if there's nothing to return
     *
     * @throws SecurityViolationException Tried to obtain cache entry using unsafe path. Generally it should never occur
     *                                    unless invalid Request is passed.
     */
    public function retrieveCachedResponse(Request $request)
    {
        if (true !== $this->isCacheable($request)) {
            return null;
        }

        $cacheElement = $this->cacheManager
            ->getElement($request->getPathInfo());

        if (null === $cacheElement) {
            return null;
        }

        $responseStatus = (($this->addStatusHeader) ? 'HIT,PHP' : null);

        return $this->responseFactory
            ->createResponseFromCacheElement($cacheElement, $responseStatus);
    }

    /**
     * @param Request $request
     *
     * @return bool|int Will return integer code if response cannot be cached or true if it's cacheable
     */
    public function isCacheable(Request $request)
    {
        if ('GET' !== $request->getMethod()) {
            return CacheManager::UNCACHEABLE_METHOD;
        }

        $queryString = $request->server
            ->get('QUERY_STRING');

        if (!empty($queryString)) {
            return CacheManager::UNCACHEABLE_QUERY;
        }

        return true;
    }
}

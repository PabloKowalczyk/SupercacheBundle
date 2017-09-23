<?php

namespace PabloK\SupercacheBundle\Cache;

use PabloK\SupercacheBundle\Exceptions\SecurityViolationException;
use PabloK\SupercacheBundle\Http\CacheResponse;
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

    public function __construct(CacheManager $cacheManager, bool $addStatusHeader)
    {
        $this->cacheManager = $cacheManager;
        $this->addStatusHeader = $addStatusHeader;
    }

    /**
     * Tries to obtain cached response.
     *
     * @param Request $request
     *
     * @return Response|null Will return cached content as response or null if there's nothing to return.
     * @throws SecurityViolationException Tried to obtain cache entry using unsafe path. Generally it should never occur
     *     unless invalid Request is passed.
     */
    public function retrieveCachedResponse(Request $request)
    {
        if ($this->isCacheable($request) !== true) {
            return null;
        }

        $cacheElement = $this->cacheManager->getElement($request->getPathInfo());
        if ($cacheElement === null) {
            return null;
        }

        return CacheResponse::createFromElement($cacheElement, (($this->addStatusHeader) ? 'HIT,PHP' : null));
    }

    /**
     * @param Request $request
     *
     * @return bool|int Will return integer code if response cannot be cached or true if it's cacheable
     */
    public function isCacheable(Request $request)
    {
        if ($request->getMethod() !== 'GET') {
            return CacheManager::UNCACHEABLE_METHOD;
        }

        $queryString = $request->server->get('QUERY_STRING');
        if (!empty($queryString)) {
            return CacheManager::UNCACHEABLE_QUERY;
        }

        return true;
    }
}

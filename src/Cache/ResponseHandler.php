<?php

declare(strict_types=1);

namespace PabloK\SupercacheBundle\Cache;

use PabloK\SupercacheBundle\Exceptions\SecurityViolationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResponseHandler
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
     * @var bool
     */
    private $supercacheEnabled;

    public function __construct(
        CacheManager $cacheManager,
        bool $addStatusHeader,
        bool $supercacheEnabled
    ) {
        $this->cacheManager = $cacheManager;
        $this->addStatusHeader = $addStatusHeader;
        $this->supercacheEnabled = $supercacheEnabled;
    }

    /**
     * Tries to cache given response.
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return bool
     *
     * @throws SecurityViolationException Tried to save cache entry wih unsafe path. Generally it should never occur
     *                                    unless invalid Request is passed.
     */
    public function cacheResponse(Request $request, Response $response)
    {
        $isCacheable = $this->isCacheable($request, $response);

        if (true !== $isCacheable) {
            $reasonCode = (int) $isCacheable;

            if ($this->addStatusHeader) {
                $response->headers
                    ->set(
                        'X-Supercache',
                        'uncacheable,' . CacheManager::getUncachableReasonFromCode($reasonCode)
                    );
            }

            return false;
        }

        $contentType = $response->headers
            ->get('Content-Type', 'application/octet-stream');

        $status = $this->cachePush(
            $request->getPathInfo(),
            $response->getContent(),
            (string) $contentType
        );

        if ($this->addStatusHeader) {
            $response->headers
                ->set('X-Supercache', 'MISS,' . (int) $status);
        }

        return $status;
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return bool|int Will return integer code if response cannot be cached or true if it's cacheable
     */
    public function isCacheable(Request $request, Response $response)
    {
        if (!$this->supercacheEnabled) {
            return CacheManager::UNCACHEABLE_DISABLED;
        }

        if (false === $request->attributes->get('_supercache')) {
            return CacheManager::UNCACHEABLE_ROUTE;
        }

        if ('GET' !== $request->getMethod()) {
            return CacheManager::UNCACHEABLE_METHOD;
        }

        $queryString = $request->server->get('QUERY_STRING');
        if (!empty($queryString)) {
            return CacheManager::UNCACHEABLE_QUERY;
        }

        //Response::isCacheable() is unusable here due to expiry & code settings
        if (!$response->isSuccessful() || $response->isEmpty()) {
            return CacheManager::UNCACHEABLE_CODE;
        }

        if ($response->headers->hasCacheControlDirective('no-store')) {
            return CacheManager::UNCACHEABLE_NO_STORE_POLICY;
        }

        if ($response->headers->hasCacheControlDirective('private')) {
            return CacheManager::UNCACHEABLE_PRIVATE;
        }

        return true;
    }

    /**
     * Saves content to cache.
     *
     * @param string $path        HTTP path
     * @param string $content     raw content to cache
     * @param string $contentType Response Content-Type. It can be just plain MIME type or like "text/html;
     *                            charset=UTF-8"
     *
     * @return bool
     *
     * @throws SecurityViolationException
     */
    private function cachePush($path, $content, $contentType)
    {
        //Guess cache type from mime. Basic rules were defined by https://github.com/kiler129/SupercacheBundle/issues/2
        if (false !== \strpos($contentType, '/javascript') || false !== \strpos($contentType, '/json')) {
            $type = CacheElement::TYPE_JAVASCRIPT;
        } elseif (false !== \strpos($contentType, 'text/')) {
            $type = CacheElement::TYPE_HTML;
        } else {
            $type = CacheElement::TYPE_BINARY;
        }

        $element = new CacheElement($path, $content, $type);

        return $this->cacheManager
            ->saveElement($element);
    }
}

<?php

declare(strict_types=1);

namespace PabloK\SupercacheBundle\Response;

use PabloK\SupercacheBundle\Cache\CacheElement;
use PabloK\SupercacheBundle\Cache\CacheType;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 */
final class ResponseFactory
{
    const MIME_TYPE_MAP = [
        CacheType::TYPE_HTML => 'text/html',
        CacheType::TYPE_JAVASCRIPT => 'application/javascript',
        CacheType::TYPE_BINARY => 'application/octet-stream',
    ];

    /**
     * Creates new Response object from given CacheElement.
     *
     * @param ?string $status Value for X-Supercache header. By default null (no header will be added).
     */
    public function createResponseFromCacheElement(CacheElement $element, string $status = null): Response
    {
        $mimeType = $this->getMimeByType($element->getType());
        $headers = ['Content-Type' => $mimeType];

        if (null !== $status) {
            $headers['X-Supercache'] = $status;
        }

        return new Response(
            $element->getContent(),
            Response::HTTP_OK,
            $headers
        );
    }

    private function getMimeByType(string $type): string
    {
        return self::MIME_TYPE_MAP[$type] ?? self::MIME_TYPE_MAP[CacheType::TYPE_BINARY];
    }
}

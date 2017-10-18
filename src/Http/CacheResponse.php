<?php

declare(strict_types=1);

namespace PabloK\SupercacheBundle\Http;

use PabloK\SupercacheBundle\Cache\CacheElement;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class CacheResponse extends SymfonyResponse
{
    /**
     * Creates new CacheResponse object from given CacheElement.
     *
     * @param CacheElement $element
     * @param null         $status  Value for X-Supercache header. By default null (no header will be added).
     *
     * @return static
     */
    public static function createFromElement(CacheElement $element, $status = null): CacheResponse
    {
        $mime = self::getMimeByType($element->getType());
        $headers = ['Content-Type' => $mime];

        if (null !== $status) {
            $headers['X-Supercache'] = $status;
        }

        return new static($element->getContent(), static::HTTP_OK, $headers);
    }

    /**
     * @deprecated Will be moved to CacheType
     */
    private static function getMimeByType($type)
    {
        switch ($type) {
            case CacheElement::TYPE_HTML:
                return 'text/html';

            case CacheElement::TYPE_JAVASCRIPT:
                return 'application/javascript';

            case CacheElement::TYPE_BINARY:
            default:
                return 'application/octet-stream';
        }
    }
}

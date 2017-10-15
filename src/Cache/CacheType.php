<?php

declare(strict_types=1);

namespace PabloK\SupercacheBundle\Cache;

class CacheType
{
    const TYPE_HTML = 'html';
    const TYPE_JAVASCRIPT = 'js';
    const TYPE_BINARY = 'bin';
    const TYPES = [
        self::TYPE_HTML,
        self::TYPE_BINARY,
        self::TYPE_JAVASCRIPT,
    ];
}

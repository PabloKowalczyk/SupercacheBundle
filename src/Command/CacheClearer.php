<?php

declare(strict_types=1);

namespace PabloK\SupercacheBundle\Command;

use PabloK\SupercacheBundle\Cache\CacheManager;
use Symfony\Component\HttpKernel\CacheClearer\CacheClearerInterface;

class CacheClearer implements CacheClearerInterface
{
    /**
     * @var CacheManager
     */
    private $manager;

    /**
     * @param CacheManager $manager
     */
    public function __construct(CacheManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Clears any caches necessary.
     *
     * @param string $cacheDir the cache directory - it's unused because SupercacheBundle uses different directory
     *                         outside standard caching directory
     */
    public function clear($cacheDir)
    {
        $this->manager->clear();
    }
}

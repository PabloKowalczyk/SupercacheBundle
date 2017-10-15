<?php

declare(strict_types=1);

namespace PabloK\SupercacheBundle\Tests\Unit\Cache;

use PabloK\SupercacheBundle\Cache\CacheElement;
use PHPUnit\Framework\TestCase;

class CacheElementTest extends TestCase
{
    public function testCreatingWithWrongTypeWillThrowException()
    {
        $this->expectException(\InvalidArgumentException::class);

        new CacheElement(
            'path',
            'some content',
            'wrong-type'
        );
    }
}

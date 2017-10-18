<?php

declare(strict_types=1);

namespace PabloK\SupercacheBundle\Tests\Unit\Filesystem;

use PabloK\SupercacheBundle\Exceptions\EmptyPathException;
use PabloK\SupercacheBundle\Filesystem\Finder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

class FinderTest extends TestCase
{
    public function testUnableToPassEmptyCacheDir()
    {
        $mockFilesystem = $this->createMock(Filesystem::class);

        $this->expectException(EmptyPathException::class);

        new Finder('', $mockFilesystem);
    }
}

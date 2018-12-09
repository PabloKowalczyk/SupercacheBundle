<?php

declare(strict_types=1);

namespace PabloK\SupercacheBundle\Tests\Unit\Filesystem;

use PabloK\SupercacheBundle\Exceptions\EmptyPathException;
use PabloK\SupercacheBundle\Filesystem\Finder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use voku\helper\HtmlMin;

class FinderTest extends TestCase
{
    public function testUnableToPassEmptyCacheDir()
    {
        /** @var Filesystem|MockObject $mockFilesystem */
        $mockFilesystem = $this->createMock(Filesystem::class);
        /** @var HtmlMin|MockObject $mockHtmlMin */
        $mockHtmlMin = $this->createMock(HtmlMin::class);

        $this->expectException(EmptyPathException::class);

        new Finder(
            '',
            $mockFilesystem,
            $mockHtmlMin
        );
    }
}

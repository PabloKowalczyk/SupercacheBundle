<?php

declare(strict_types=1);

namespace PabloK\SupercacheBundle\Tests\Unit\Response;

use PabloK\SupercacheBundle\Cache\CacheElement;
use PabloK\SupercacheBundle\Cache\CacheType;
use PabloK\SupercacheBundle\Response\ResponseFactory;
use PHPUnit\Framework\TestCase;

class ResponseFactoryTest extends TestCase
{
    /**
     * @dataProvider cacheDataProvider
     */
    public function testCreateResponseFromCacheElement(
        $status,
        string $cacheElementType,
        string $expectedMimeType
    ) {
        /** @var CacheElement|\PHPUnit_Framework_MockObject_MockObject $mockCacheElement */
        $mockCacheElement = $this->createMock(CacheElement::class);
        $mockCacheElement
            ->method('getType')
            ->willReturn($cacheElementType);

        $responseFactory = new ResponseFactory();

        $response = $responseFactory->createResponseFromCacheElement($mockCacheElement, $status);

        $headersBag = $response->headers;

        $this->assertSame($status, $headersBag->get('x-supercache'));
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expectedMimeType, $headersBag->get('content-type'));
    }

    public function cacheDataProvider()
    {
        return [
            'nullStatus' => [
                null,
                CacheType::TYPE_HTML,
                'text/html',
            ],
            'htmlType' => [
                'a',
                CacheType::TYPE_HTML,
                'text/html',
            ],
            'jsType' => [
                'some-status',
                CacheType::TYPE_JAVASCRIPT,
                'application/javascript',
            ],
            'binType' => [
                'some-other-status',
                CacheType::TYPE_BINARY,
                'application/octet-stream',
            ],
        ];
    }
}

<?php

declare(strict_types=1);

namespace PabloK\SupercacheBundle\Tests\Unit\Response;

use PabloK\SupercacheBundle\Cache\ResponseHandler;
use PabloK\SupercacheBundle\Response\Handler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class HandlerTest extends TestCase
{
    /** @test */
    public function responseFromCacheWillStopPropagation(): void
    {
        $mockResponseHandler = $this->mockResponseHandler(0);
        $mockFilterResponseEvent = $this->mockFilterResponseEvent(
            Handler::RESPONSE_SOURCE_CACHE,
            1,
            true
        );

        $responseHandler = new Handler($mockResponseHandler);

        $responseHandler($mockFilterResponseEvent);
    }

    /** @test */
    public function doNothingOnMasterRequest(): void
    {
        $mockResponseHandler = $this->mockResponseHandler(0);
        $mockFilterResponseEvent = $this->mockFilterResponseEvent(
            'other-source',
            0,
            false
        );

        $responseHandler = new Handler($mockResponseHandler);

        $responseHandler($mockFilterResponseEvent);
    }

    /** @test */
    public function cacheResponseWhenNotCachedAndMasterRequest()
    {
        $mockResponseHandler = $this->mockResponseHandler(1);
        $mockFilterResponseEvent = $this->mockFilterResponseEvent(
            'test-source',
            0,
            true
        );

        $responseHandler = new Handler($mockResponseHandler);

        $responseHandler($mockFilterResponseEvent);
    }

    private function mockFilterResponseEvent(
        string $responseSource,
        int $stopPropagationCount,
        bool $isMasterRequest
    ): FilterResponseEvent {
        /** @var FilterResponseEvent|\PHPUnit_Framework_MockObject_MockObject $mockFilterResponseEvent */
        $mockFilterResponseEvent = $this->createMock(FilterResponseEvent::class);
        /** @var Request|\PHPUnit_Framework_MockObject_MockObject $mockRequest */
        $mockRequest = $this->createMock(Request::class);
        /** @var ParameterBag|\PHPUnit_Framework_MockObject_MockObject $mockParameterBag */
        $mockParameterBag = $this->createMock(ParameterBag::class);
        $mockResponse = $this->createMock(Response::class);

        $mockParameterBag
            ->method('get')
            ->willReturn($responseSource);

        $mockRequest->attributes = $mockParameterBag;

        $mockFilterResponseEvent
            ->method('getRequest')
            ->willReturn($mockRequest);
        $mockFilterResponseEvent
            ->expects($this->exactly($stopPropagationCount))
            ->method('stopPropagation');
        $mockFilterResponseEvent
            ->method('isMasterRequest')
            ->willReturn($isMasterRequest);
        $mockFilterResponseEvent
            ->method('getResponse')
            ->willReturn($mockResponse);

        return $mockFilterResponseEvent;
    }

    private function mockResponseHandler(int $cacheResponseCallsCount): ResponseHandler
    {
        /** @var ResponseHandler|\PHPUnit_Framework_MockObject_MockObject $mockResponseHandler */
        $mockResponseHandler = $this->createMock(ResponseHandler::class);

        $mockResponseHandler
            ->expects($this->exactly($cacheResponseCallsCount))
            ->method('cacheResponse');

        return $mockResponseHandler;
    }
}

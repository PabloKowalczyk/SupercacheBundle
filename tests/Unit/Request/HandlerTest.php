<?php

declare(strict_types=1);

namespace PabloK\SupercacheBundle\Tests\Unit\Request;

use PabloK\SupercacheBundle\Cache\RequestHandler;
use PabloK\SupercacheBundle\Request\Handler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class HandlerTest extends TestCase
{
    /** @test */
    public function doNothingOnEmptyCachedResponse(): void
    {
        $mockRequestHandler = $this->createMock(RequestHandler::class);
        $mockGetResponseEvent = $this->createMock(GetResponseEvent::class);

        $mockGetResponseEvent
            ->method('getRequest')
            ->willReturn($this->createMock(Request::class));

        $mockGetResponseEvent
            ->expects($this->never())
            ->method('setResponse');

        $handler = new Handler($mockRequestHandler);

        $handler($mockGetResponseEvent);
    }

    /** @test */
    public function setResponseWhenResponseFoundInCache(): void
    {
        $mockRequestHandler = $this->createMock(RequestHandler::class);
        $mockGetResponseEvent = $this->createMock(GetResponseEvent::class);
        $mockRequest = $this->createMock(Request::class);

        $mockRequest->attributes = new ParameterBag();

        $mockGetResponseEvent
            ->method('getRequest')
            ->willReturn($mockRequest);

        $mockRequestHandler
            ->method('retrieveCachedResponse')
            ->willReturn($this->createMock(Response::class));

        $mockGetResponseEvent
            ->expects($this->once())
            ->method('setResponse');

        $handler = new Handler($mockRequestHandler);

        $handler($mockGetResponseEvent);
    }
}

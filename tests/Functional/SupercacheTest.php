<?php

declare(strict_types=1);

namespace PabloK\SupercacheBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Response;

class SupercacheTest extends WebTestCase
{
    /**
     * @var string
     */
    private $supercacheCacheDirectory;
    /**
     * @var Container
     */
    private $container;
    /**
     * @var Client
     */
    private $client;

    public function setUp()
    {
        $this->client = static::createClient();
        $this->container = static::$kernel->getContainer();
        $this->supercacheCacheDirectory = $this->container
            ->getParameter('supercache.cache_dir');
    }

    protected function tearDown()
    {
        // Clear cache directory
        foreach (new \DirectoryIterator($this->supercacheCacheDirectory) as $fileInfo) {
            if ($fileInfo->isDot()) {
                continue;
            }

            \unlink($fileInfo->getPathname());
        }

        parent::tearDown();
    }

    public function testResponsesAreStoredInCacheDirectory()
    {
        $response = $this->makeRequest();

        $this->assertSame(200, $response->getStatusCode());
        $this->assertFileExists("{$this->supercacheCacheDirectory}/index.html");
    }

    public function testResponsesAreMinified()
    {
        $response = $this->makeRequest();

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(
            '<html><body><h1>Testing env welcome to.</h1>',
            \file_get_contents("{$this->supercacheCacheDirectory}/index.html")
        );
    }

    public function testResponseHasMissHeaderOnFirstRequest()
    {
        $response = $this->makeRequest();

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSupercacheHeader($response, 'MISS,1');
    }

    public function testResponseHasHitHeaderOnSecondRequest()
    {
        $this->makeRequest();
        $response = $this->makeRequest();

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSupercacheHeader($response, 'HIT,PHP');
    }

    private function makeRequest(): Response
    {
        $this->client
            ->request('GET', '');

        $response = $this->client
            ->getResponse();

        return $response;
    }

    private function assertSupercacheHeader(Response $response, string $expectedHeaderValue)
    {
        $supercacheHeader = $response->headers
            ->get('x-supercache');

        $this->assertSame($expectedHeaderValue, $supercacheHeader);
    }
}

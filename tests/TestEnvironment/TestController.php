<?php

declare(strict_types=1);

namespace PabloK\SupercacheBundle\Tests\TestEnvironment;

use Symfony\Component\HttpFoundation\Response;

class TestController
{
    public function indexAction()
    {
        $response = new Response("<html><body><h1>Testing env welcome to.</h1></body></html>");

        $response->setPublic();

        return $response;
    }
}

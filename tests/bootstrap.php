<?php

declare(strict_types=1);

use PabloK\SupercacheBundle\Tests\TestEnvironment\TestKernel;

require_once __DIR__ . '/../vendor/autoload.php';

class_alias(TestKernel::class, \TestKernel::class);

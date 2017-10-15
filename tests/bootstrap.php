<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

if (!\class_exists(\PHPUnit_Framework_TestCase::class)) {
    \class_alias(\PHPUnit\Framework\TestCase::class, \PHPUnit_Framework_TestCase::class);
}

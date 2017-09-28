<?php

declare(strict_types=1);

namespace PabloK\SupercacheBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ClearCommandTest extends KernelTestCase
{
    public function testCommand()
    {
        $kernel = $this->createKernel();
        $kernel->boot();

        $container = $kernel->getContainer();

        $command = $container->get("supercache.command.clear_command");

        $commandTester = new CommandTester($command);

        $commandTester->execute([]);

        $this->assertRegExp("@Clearing cache directory\sSuccess@", $commandTester->getDisplay());
    }
}

<?php

declare(strict_types=1);

namespace PabloK\SupercacheBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ClearCommandTest extends KernelTestCase
{
    public function testCommand()
    {
        $kernel = $this->createKernel();
        $kernel->boot();

        $container = $kernel->getContainer();

        $command = $container->get("console.command.pablok_supercachebundle_command_clearcommand");

        $commandTester = new CommandTester($command);

        $commandTester->execute([]);

        $this->assertRegExp("@Clearing cache directory\sSuccess@", $commandTester->getDisplay());
    }
}

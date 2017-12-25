<?php

declare(strict_types=1);

namespace PabloK\SupercacheBundle\Tests\Functional;

use PabloK\SupercacheBundle\Command\ClearCommand;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ClearCommandTest extends KernelTestCase
{
    public function testCommand()
    {
        self::bootKernel();

        $container = self::$kernel->getContainer();

        /** @var ClearCommand $command */
        $command = $container->get('console.command.pablok_supercachebundle_command_clearcommand');

        $commandTester = new CommandTester($command);

        $commandTester->execute([]);

        $this->assertRegExp("@Clearing cache directory\sSuccess@", $commandTester->getDisplay());
    }
}

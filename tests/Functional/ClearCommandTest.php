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

        $commandId = 'console.command.pablok_supercachebundle_command_clearcommand';

        if (!$container->has($commandId)) {
            // Symfony 4.0.3+ format
            $commandId = 'console.command.public_alias.supercache.command.clear_command';
        }

        /** @var ClearCommand $command */
        $command = $container->get($commandId);

        $commandTester = new CommandTester($command);

        $commandTester->execute([]);

        $this->assertRegExp("@Clearing cache directory\sSuccess@", $commandTester->getDisplay());
    }
}

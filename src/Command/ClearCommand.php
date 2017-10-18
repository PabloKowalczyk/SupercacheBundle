<?php

declare(strict_types=1);

namespace PabloK\SupercacheBundle\Command;

use PabloK\SupercacheBundle\Cache\CacheManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ClearCommand extends Command
{
    /**
     * @var CacheManager
     */
    private $cacheManager;

    public function __construct(CacheManager $cacheManager)
    {
        $this->cacheManager = $cacheManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('supercache:clear')
            ->setDescription('Clears all cache entries');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $clearResult = $this->cacheManager
            ->clear();

        $textResult = $clearResult ? '<info>Success</info>' : '<error>Failed - try manually clearing cache directory</error>';

        $output->writeln('Clearing cache directory');
        $output->writeln($textResult);
    }
}

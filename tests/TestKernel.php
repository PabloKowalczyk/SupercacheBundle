<?php

declare(strict_types=1);

use PabloK\SupercacheBundle\SupercacheBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class TestKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            new FrameworkBundle(),
            new SupercacheBundle()
        ];

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir() . DIRECTORY_SEPARATOR . 'config.yml');
    }

    public function getCacheDir()
    {
        return $this->getVarDir(). DIRECTORY_SEPARATOR . 'cache';
    }

    public function getLogDir()
    {
        return $this->getVarDir() . DIRECTORY_SEPARATOR . "logs";
    }

    private function getVarDir()
    {
        $varDir = [
            sys_get_temp_dir(),
            "supercache",
            "var"
        ];

        return implode(DIRECTORY_SEPARATOR, $varDir);
    }
}

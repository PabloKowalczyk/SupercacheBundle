<?php

declare(strict_types=1);

namespace PabloK\SupercacheBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class SupercacheExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $loader->load('services.xml');

        $container->setParameter('supercache.enabled', $config['enabled']);
        $container->setParameter('supercache.cache_dir', $config['cache_dir']);
        $container->setParameter('supercache.cache_status_header', $config['cache_status_header']);
    }
}

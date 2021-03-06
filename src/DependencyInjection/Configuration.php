<?php

declare(strict_types=1);

namespace PabloK\SupercacheBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('supercache');

        if (\method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // BC layer for symfony/config 4.1 and older
            $rootNode = $treeBuilder->root('supercache');
        }

        $rootNode
            ->children()
                ->booleanNode('enabled')
                    ->defaultFalse()
                    ->info('Enable/disable cache')
                ->end()
                ->scalarNode('cache_dir')
                    ->defaultValue('%kernel.root_dir%/../webcache')
                    ->info('Cache directory, must be http-accessible (so it cannot be located under app/)')
                ->end()
                ->booleanNode('cache_status_header')
                    ->defaultTrue()
                    ->info('Enable/disable adding X-Supercache header')
                ->end()
            ->end();

        return $treeBuilder;
    }
}

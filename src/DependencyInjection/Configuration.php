<?php

namespace Incenteev\HashedAssetBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('incenteev_hashed_asset');

        $rootNode->children()
            ->scalarNode('web_root')->defaultValue('%kernel.root_dir%/../web')->end()
            ->scalarNode('version_format')->defaultValue('%%s?%%s')->end()
        ;

        return $treeBuilder;
    }
}

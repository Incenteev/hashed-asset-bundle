<?php

namespace Incenteev\HashedAssetBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Flex\Recipe;

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
            ->scalarNode('web_root')->defaultValue(\class_exists(Recipe::class) ? '%kernel.project_dir%/public' : '%kernel.root_dir%/../web')->end()
            ->scalarNode('version_format')->defaultValue('%%s?%%s')->end()
        ;

        return $treeBuilder;
    }
}

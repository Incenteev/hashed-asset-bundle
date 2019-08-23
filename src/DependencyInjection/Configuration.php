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
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('incenteev_hashed_asset');

        if (\method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // BC layer for symfony/config 4.1 and older
            $rootNode = $treeBuilder->root('incenteev_hashed_asset');
        }

        $rootNode->children()
            ->scalarNode('web_root')->defaultValue(\class_exists(Recipe::class) ? '%kernel.project_dir%/public' : '%kernel.root_dir%/../web')->end()
            ->scalarNode('version_format')->defaultValue('%%s?%%s')->end()
        ;

        return $treeBuilder;
    }
}

<?php

namespace Incenteev\HashedAssetBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class IncenteevHashedAssetExtension extends ConfigurableExtension
{
    /**
     * @param array{web_root: string, version_format: string} $config
     */
    protected function loadInternal(array $config, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $container->getDefinition('incenteev_hashed_asset.file_hasher')
            ->replaceArgument(0, $config['web_root']);

        $container->getDefinition('incenteev_hashed_asset.strategy')
            ->replaceArgument(1, $config['version_format']);

        if (!$container->getParameter('kernel.debug')) {
            $loader->load('cache.xml');

            $container->getDefinition('incenteev_hashed_asset.asset_finder')
                ->replaceArgument(0, $config['web_root']);
        }
    }
}

<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Incenteev\HashedAssetBundle\Asset\HashingVersionStrategy;
use Incenteev\HashedAssetBundle\Hashing\FileHasher;

return static function(ContainerConfigurator $container) {
    $services = $container->services();
    $parameters = $container->parameters();

    $services->set('incenteev_hashed_asset.strategy', HashingVersionStrategy::class)
        ->private()
        ->args([
            service('incenteev_hashed_asset.asset_hasher'),
            abstract_arg('Set in the extension'),
        ]);

    $services->alias('incenteev_hashed_asset.asset_hasher', 'incenteev_hashed_asset.file_hasher')
        ->private();

    $services->set('incenteev_hashed_asset.file_hasher', FileHasher::class)
        ->private()
        ->args([abstract_arg('Set in the extension')]);
};

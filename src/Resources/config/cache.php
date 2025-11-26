<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Incenteev\HashedAssetBundle\CacheWarmer\AssetFinder;
use Incenteev\HashedAssetBundle\CacheWarmer\HashCacheWarmer;
use Incenteev\HashedAssetBundle\Hashing\CachedHasher;
use Symfony\Component\Cache\Adapter\PhpArrayAdapter;

return static function(ContainerConfigurator $container) {
    $services = $container->services();
    $parameters = $container->parameters();
    $parameters->set('incenteev_hashed_asset.cache.file', '%kernel.cache_dir%/incenteev_asset_hashes.php');

    $services->set('incenteev_hashed_asset.cached_hasher', CachedHasher::class)
        ->private()
        ->decorate('incenteev_hashed_asset.asset_hasher')
        ->args([
            service('incenteev_hashed_asset.cached_hasher.inner'),
            inline_service(PhpArrayAdapter::class)
                ->args([
                    '%incenteev_hashed_asset.cache.file%',
                    service('cache.incenteev_hashed_asset'),
                ])
                ->factory([PhpArrayAdapter::class, 'create']),
        ]);

    $services->set('incenteev_hashed_asset.cache_warmer', HashCacheWarmer::class)
        ->private()
        ->args([
            service('incenteev_hashed_asset.asset_finder'),
            '%incenteev_hashed_asset.cache.file%',
            service('incenteev_hashed_asset.file_hasher'),
            service('cache.incenteev_hashed_asset'),
        ])
        ->tag('kernel.cache_warmer');

    $services->set('incenteev_hashed_asset.asset_finder', AssetFinder::class)
        ->private()
        ->args([abstract_arg('Set in the extension')]);

    $services->set('cache.incenteev_hashed_asset')
        ->private()
        ->parent('cache.system')
        ->tag('cache.pool');
};

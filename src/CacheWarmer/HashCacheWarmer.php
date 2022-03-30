<?php

namespace Incenteev\HashedAssetBundle\CacheWarmer;

use Incenteev\HashedAssetBundle\Hashing\AssetHasherInterface;
use Incenteev\HashedAssetBundle\Hashing\CachedHasher;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\PhpArrayAdapter;
use Symfony\Component\Cache\Adapter\ProxyAdapter;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;

final class HashCacheWarmer implements CacheWarmerInterface
{
    private AssetFinder $assetFinder;
    private string $cacheFile;
    private AssetHasherInterface $hasher;
    private AdapterInterface $fallbackPool;

    public function __construct(AssetFinder $assetFinder, string $cacheFile, AssetHasherInterface $hasher, CacheItemPoolInterface $fallbackPool)
    {
        $this->assetFinder = $assetFinder;
        $this->cacheFile = $cacheFile;
        $this->hasher = $hasher;

        if (!$fallbackPool instanceof AdapterInterface) {
            $fallbackPool = new ProxyAdapter($fallbackPool);
        }

        $this->fallbackPool = $fallbackPool;
    }

    public function warmUp($cacheDir): array
    {
        $phpArrayPool = new PhpArrayAdapter($this->cacheFile, $this->fallbackPool);
        $arrayPool = new ArrayAdapter(0, false);

        $hasher = new CachedHasher($this->hasher, $arrayPool);

        foreach ($this->assetFinder->getAssetPaths() as $path) {
            $hasher->computeHash($path);
        }

        $values = $arrayPool->getValues();
        $phpArrayPool->warmUp($values);

        foreach ($values as $k => $v) {
            $item = $this->fallbackPool->getItem($k);
            $this->fallbackPool->saveDeferred($item->set($v));
        }
        $this->fallbackPool->commit();

        return [];
    }

    public function isOptional(): bool
    {
        return true;
    }
}

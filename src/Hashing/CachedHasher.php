<?php

namespace Incenteev\HashedAssetBundle\Hashing;

use Psr\Cache\CacheItemPoolInterface;

final class CachedHasher implements AssetHasherInterface
{
    private AssetHasherInterface $hasher;
    private CacheItemPoolInterface $cache;

    public function __construct(AssetHasherInterface $hasher, CacheItemPoolInterface $cache)
    {
        $this->hasher = $hasher;
        $this->cache = $cache;
    }

    public function computeHash(string $path): string
    {
        // The hashing implementation does not care about leading slashes in the path, so share cache keys for them
        $item = $this->cache->getItem(base64_encode(ltrim($path, '/')));

        if ($item->isHit()) {
            $cachedHash = $item->get();

            if (\is_string($cachedHash)) {
                return $cachedHash;
            }
        }

        $hash = $this->hasher->computeHash($path);

        $item->set($hash);
        $this->cache->save($item);

        return $hash;
    }
}

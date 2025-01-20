<?php

namespace Incenteev\HashedAssetBundle\Asset;

use Incenteev\HashedAssetBundle\Hashing\AssetHasherInterface;
use Symfony\Component\Asset\VersionStrategy\VersionStrategyInterface;

class HashingVersionStrategy implements VersionStrategyInterface
{
    private AssetHasherInterface $hasher;
    private string $format;

    public function __construct(AssetHasherInterface $hasher, ?string $format = null)
    {
        $this->format = $format ?: '%s?%s';
        $this->hasher = $hasher;
    }

    /**
     * @param string $path
     */
    public function getVersion($path): string
    {
        return $this->hasher->computeHash($path);
    }

    /**
     * @param string $path
     */
    public function applyVersion($path): string
    {
        $versionized = sprintf($this->format, ltrim($path, '/'), $this->hasher->computeHash($path));

        if ($path && '/' === $path[0]) {
            return '/'.$versionized;
        }

        return $versionized;
    }
}

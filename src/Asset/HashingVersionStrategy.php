<?php

namespace Incenteev\HashedAssetBundle\Asset;

use Incenteev\HashedAssetBundle\Hashing\AssetHasherInterface;
use Symfony\Component\Asset\VersionStrategy\VersionStrategyInterface;

class HashingVersionStrategy implements VersionStrategyInterface
{
    private $hasher;
    private $format;

    public function __construct(AssetHasherInterface $hasher, string $format = null)
    {
        $this->format = $format ?: '%s?%s';
        $this->hasher = $hasher;
    }

    public function getVersion($path): string
    {
        return $this->hasher->computeHash($path);
    }

    public function applyVersion($path): string
    {
        $versionized = sprintf($this->format, ltrim($path, '/'), $this->hasher->computeHash($path));

        if ($path && '/' === $path[0]) {
            return '/'.$versionized;
        }

        return $versionized;
    }
}

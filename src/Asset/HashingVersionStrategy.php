<?php

namespace Incenteev\HashedAssetBundle\Asset;

use Symfony\Component\Asset\VersionStrategy\VersionStrategyInterface;

class HashingVersionStrategy implements VersionStrategyInterface
{
    private $webRoot;
    private $format;

    public function __construct(string $webRoot, string $format = null)
    {
        $this->format = $format ?: '%s?%s';
        $this->webRoot = $webRoot;
    }

    public function getVersion($path)
    {
        $fullPath = $this->webRoot.'/'.ltrim($path, '/');

        if (!is_file($fullPath)) {
            return '';
        }

        return substr(sha1_file($fullPath), 0, 7);
    }

    public function applyVersion($path)
    {
        $versionized = sprintf($this->format, ltrim($path, '/'), $this->getVersion($path));

        if ($path && '/' === $path[0]) {
            return '/'.$versionized;
        }

        return $versionized;
    }
}

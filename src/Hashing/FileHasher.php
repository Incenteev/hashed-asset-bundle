<?php

namespace Incenteev\HashedAssetBundle\Hashing;

final class FileHasher implements AssetHasherInterface
{
    private string $webRoot;

    public function __construct(string $webRoot)
    {
        $this->webRoot = $webRoot;
    }

    public function computeHash(string $path): string
    {
        $fullPath = $this->webRoot.'/'.ltrim($path, '/');

        if (!is_file($fullPath)) {
            return '';
        }

        $hash = sha1_file($fullPath);

        if ($hash === false) {
            return '';
        }

        return substr($hash, 0, 7);
    }
}

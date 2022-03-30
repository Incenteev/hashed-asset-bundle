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

        return substr(sha1_file($fullPath), 0, 7);
    }
}

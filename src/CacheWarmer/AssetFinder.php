<?php

namespace Incenteev\HashedAssetBundle\CacheWarmer;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * @internal
 */
class AssetFinder
{
    private string $webRoot;

    public function __construct(string $webRoot)
    {
        $this->webRoot = $webRoot;
    }

    /**
     * @return \Traversable<string>
     */
    public function getAssetPaths(): \Traversable
    {
        $finder = (new Finder())->files()
            ->in($this->webRoot);

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            yield $file->getRelativePathname();
        }
    }
}

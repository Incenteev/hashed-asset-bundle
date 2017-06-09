<?php

namespace Incenteev\HashedAssetBundle\Tests\CacheWarmer;

use Incenteev\HashedAssetBundle\CacheWarmer\AssetFinder;
use PHPUnit\Framework\TestCase;

class AssetFinderTest extends TestCase
{
    public function testGetAssetPaths()
    {
        $assetFinder = new AssetFinder(__DIR__.'/../Hashing/fixtures');

        $assetPaths = iterator_to_array($assetFinder->getAssetPaths());

        $this->assertCount(3, $assetPaths);
        $this->assertContains('asset1.txt', $assetPaths);
        $this->assertContains('asset2.txt', $assetPaths);
        $this->assertContains('subdir/asset3.txt', $assetPaths);
    }
}

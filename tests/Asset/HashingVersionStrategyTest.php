<?php

namespace Incenteev\HashedAssetBundle\Tests\Asset;

use Incenteev\HashedAssetBundle\Asset\HashingVersionStrategy;
use PHPUnit\Framework\TestCase;

class HashingVersionStrategyTest extends TestCase
{
    /**
     * @dataProvider getAssetVersions
     */
    public function testGetVersion($path, $version)
    {
        $versionStrategy = new HashingVersionStrategy(__DIR__.'/fixtures');

        $this->assertEquals($version, $versionStrategy->getVersion($path));
    }

    public static function getAssetVersions()
    {
        yield ['asset1.txt', 'd0c0575'];
        yield ['asset2.txt', 'c1cf85a'];
        yield ['/asset2.txt', 'c1cf85a'];
        yield ['asset3.txt', ''];
    }

    /**
     * @dataProvider getVersionedAssets
     */
    public function testApplyVersion($path, $expected, $format = null)
    {
        $versionStrategy = new HashingVersionStrategy(__DIR__.'/fixtures', $format);

        $this->assertEquals($expected, $versionStrategy->applyVersion($path));
    }

    public static function getVersionedAssets()
    {
        yield ['asset1.txt', 'asset1.txt?d0c0575'];
        yield ['asset2.txt', 'asset2.txt?c1cf85a'];
        yield ['/asset2.txt', '/asset2.txt?c1cf85a'];
        yield ['asset3.txt', 'asset3.txt?'];
        yield ['asset2.txt', 'c1cf85a/asset2.txt', '%2$s/%1$s'];
        yield ['/asset2.txt', '/c1cf85a/asset2.txt', '%2$s/%1$s'];
        yield ['/asset2.txt', '/c1cf85a/asset2.txt', '%2$s/%s'];
    }
}

<?php

namespace Incenteev\HashedAssetBundle\Tests\Asset;

use Incenteev\HashedAssetBundle\Asset\HashingVersionStrategy;
use Incenteev\HashedAssetBundle\Hashing\AssetHasherInterface;
use PHPUnit\Framework\TestCase;

class HashingVersionStrategyTest extends TestCase
{
    public function testGetVersion()
    {
        $hasher = $this->prophesize(AssetHasherInterface::class);
        $hasher->computeHash('test')->willReturn('foo');

        $versionStrategy = new HashingVersionStrategy($hasher->reveal());

        $this->assertEquals('foo', $versionStrategy->getVersion('test'));
    }

    /**
     * @dataProvider getVersionedAssets
     */
    public function testApplyVersion($path, $expected, $hash, $format = null)
    {
        $hasher = $this->prophesize(AssetHasherInterface::class);
        $hasher->computeHash($path)->willReturn($hash);

        $versionStrategy = new HashingVersionStrategy($hasher->reveal(), $format);

        $this->assertEquals($expected, $versionStrategy->applyVersion($path));
    }

    public static function getVersionedAssets()
    {
        yield ['asset1.txt', 'asset1.txt?d0c0575', 'd0c0575'];
        yield ['asset2.txt', 'asset2.txt?c1cf85a', 'c1cf85a'];
        yield ['/asset2.txt', '/asset2.txt?c1cf85a', 'c1cf85a'];
        yield ['asset3.txt', 'asset3.txt?', ''];
        yield ['asset2.txt', 'c1cf85a/asset2.txt', 'c1cf85a', '%2$s/%1$s'];
        yield ['/asset2.txt', '/c1cf85a/asset2.txt', 'c1cf85a', '%2$s/%1$s'];
        yield ['/asset2.txt', '/c1cf85a/asset2.txt', 'c1cf85a', '%2$s/%s'];
    }
}

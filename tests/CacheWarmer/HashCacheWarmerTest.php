<?php

namespace Incenteev\HashedAssetBundle\Tests\CacheWarmer;

use Incenteev\HashedAssetBundle\CacheWarmer\AssetFinder;
use Incenteev\HashedAssetBundle\CacheWarmer\HashCacheWarmer;
use Incenteev\HashedAssetBundle\Hashing\AssetHasherInterface;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\PhpArrayAdapter;

class HashCacheWarmerTest extends TestCase
{
    public function testWarmUp()
    {
        $hasher = $this->prophesize(AssetHasherInterface::class);
        $hasher->computeHash('foo')->willReturn('foohash');
        $hasher->computeHash('bar/baz.js')->willReturn('bazhash');

        $assetFinder = $this->prophesize(AssetFinder::class);
        $assetFinder->getAssetPaths()->willReturn(new \ArrayIterator(array('foo', 'bar/baz.js')));

        $file = sys_get_temp_dir().'/incenteev-asset-hash-cache.php';
        @unlink($file);

        $fallbackPool = new ArrayAdapter();

        $warmer = new HashCacheWarmer($assetFinder->reveal(), $file, $hasher->reveal(), $fallbackPool);
        $warmer->warmUp(dirname($file));

        $this->assertFileExists($file);

        $cacheAdapter = new PhpArrayAdapter($file, new ArrayAdapter());

        $fooItem = $cacheAdapter->getItem(base64_encode('foo'));

        $this->assertTrue($fooItem->isHit());
        $this->assertSame('foohash', $fooItem->get());

        $bazItem = $cacheAdapter->getItem(base64_encode('bar/baz.js'));

        $this->assertTrue($bazItem->isHit());
        $this->assertSame('bazhash', $bazItem->get());

        $values = $fallbackPool->getValues();

        $this->assertInternalType('array', $values);
        $this->assertCount(2, $values);
        $this->assertArrayHasKey(base64_encode('foo'), $values);
        $this->assertArrayHasKey(base64_encode('bar/baz.js'), $values);
    }

    public function testOptional()
    {
        $assetFinder = $this->prophesize(AssetFinder::class)->reveal();
        $hasher = $this->prophesize(AssetHasherInterface::class)->reveal();
        $cachePool = $this->prophesize(CacheItemPoolInterface::class)->reveal();

        $warmer = new HashCacheWarmer($assetFinder, __DIR__.'/fake', $hasher, $cachePool);

        $this->assertTrue($warmer->isOptional());
    }
}

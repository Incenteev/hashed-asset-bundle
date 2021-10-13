<?php

namespace Incenteev\HashedAssetBundle\Tests\Hashing;

use Incenteev\HashedAssetBundle\Hashing\AssetHasherInterface;
use Incenteev\HashedAssetBundle\Hashing\CachedHasher;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

class CachedHasherTest extends TestCase
{
    use ProphecyTrait;

    public function testComputeHash()
    {
        $delegateHasher = $this->prophesize(AssetHasherInterface::class);
        $delegateHasher->computeHash('foo')->willReturn('foohash');
        $delegateHasher->computeHash('bar/baz.js')->willReturn('bazhash');

        $cache = new ArrayAdapter(0, false);

        $hasher = new CachedHasher($delegateHasher->reveal(), $cache);

        $this->assertSame('foohash', $hasher->computeHash('foo'));
        $this->assertSame('bazhash', $hasher->computeHash('bar/baz.js'));
        $this->assertSame('bazhash', $hasher->computeHash('/bar/baz.js'));
        $this->assertSame('foohash', $hasher->computeHash('foo'));

        $delegateHasher->computeHash('foo')->shouldHaveBeenCalledTimes(1);
        $delegateHasher->computeHash('bar/baz.js')->shouldHaveBeenCalledTimes(1);
    }
}

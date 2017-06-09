<?php

namespace Incenteev\HashedAssetBundle\Tests\Hashing;

use Incenteev\HashedAssetBundle\Hashing\FileHasher;
use PHPUnit\Framework\TestCase;

class FileHasherTest extends TestCase
{
    /**
     * @dataProvider getAssetVersions
     */
    public function testComputeHash($path, $version)
    {
        $versionStrategy = new FileHasher(__DIR__.'/fixtures');

        $this->assertEquals($version, $versionStrategy->computeHash($path));
    }

    public static function getAssetVersions()
    {
        yield ['asset1.txt', 'd0c0575'];
        yield ['asset2.txt', 'c1cf85a'];
        yield ['/asset2.txt', 'c1cf85a'];
        yield ['asset3.txt', ''];
    }
}

<?php

namespace Incenteev\HashedAssetBundle\Tests\DependencyInjection;

use Incenteev\HashedAssetBundle\DependencyInjection\IncenteevHashedAssetExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class IncenteevHashedAssetExtensionTest extends TestCase
{
    public function testDefaultConfig()
    {
        $extension = new IncenteevHashedAssetExtension();
        $container = new ContainerBuilder();

        $extension->load(array(array()), $container);

        $this->assertHasDefinition($container, 'incenteev_hashed_asset.strategy');
        $def = $container->getDefinition('incenteev_hashed_asset.strategy');

        $this->assertEquals('%kernel.root_dir%/../web', $def->getArgument(0));
        $this->assertEquals('%%s?%%s', $def->getArgument(1));
    }

    public function testConfigured()
    {
        $extension = new IncenteevHashedAssetExtension();
        $container = new ContainerBuilder();

        $extension->load(array(array(
            'version_format' => '%%s?v=%%s',
            'web_root' => '/var/html/test',
        )), $container);

        $this->assertHasDefinition($container, 'incenteev_hashed_asset.strategy');
        $def = $container->getDefinition('incenteev_hashed_asset.strategy');

        $this->assertEquals('/var/html/test', $def->getArgument(0));
        $this->assertEquals('%%s?v=%%s', $def->getArgument(1));
    }

    private function assertHasDefinition(ContainerBuilder $containerBuilder, string $id)
    {
        $this->assertTrue($containerBuilder->hasDefinition($id), sprintf('The container has a `%s` service definition.', $id));
    }
}

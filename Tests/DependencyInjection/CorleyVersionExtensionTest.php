<?php
namespace Corley\VersionBundle\Tests\DependencyInjection;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Corley\VersionBundle\DependencyInjection\CorleyVersionExtension;

class CorleyVersionExtensionTest extends \PHPUnit_Framework_TestCase
{
    private $container;

    public function setUp()
    {
        $this->kernel = $this->getMock('Symfony\\Component\\HttpKernel\\KernelInterface');

        $this->container = new ContainerBuilder();

        $this->container->setParameter('kernel.root_dir', __DIR__);
        $this->container->set('kernel', $this->kernel);
    }

    public function testBumpCommandCorrectlyConfigured()
    {
        $extension = new CorleyVersionExtension();
        $extension->load(array(array()), $this->container);

        $this->assertInstanceOf(
            'Corley\VersionBundle\Command\BumpCommand',
            $this->container->get('corley_version.command.version_command')
        );
    }

    public function testShowCommandCorrectlyConfigured()
    {
        $extension = new CorleyVersionExtension();
        $extension->load(array(array()), $this->container);

        $this->assertInstanceOf(
            'Corley\VersionBundle\Command\ShowCommand',
            $this->container->get('corley_version.command.show_version_command')
        );
    }
}

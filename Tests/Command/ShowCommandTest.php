<?php
namespace Corley\VersionBundle\Tests\Command;

use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Corley\VersionBundle\Command\ShowCommand as VersionCommand;
use org\bovigo\vfs\vfsStream;
use Symfony\Component\Yaml\Parser;

class ShowCommandTest extends \PHPUnit_Framework_TestCase
{
    private $kernel;
    private $parser;
    private $root;

    public function setUp()
    {
        $this->root = vfsStream::setup('config');
    }

    private function prepareCommand()
    {
        $this->kernel = $this->getMock('Symfony\\Component\\HttpKernel\\KernelInterface');
        $this->parser = new parser();

        $application = new Application($this->kernel);
        $application->add(new VersionCommand($this->parser));

        $command = $application->find('corley:version:show');
        $command->setRootDir(vfsStream::url('config'));

        $commandTester = new CommandTester($command);

        return $commandTester;
    }

    /**
     * @group functional
     * @group command
     */
    public function testBumpANewVersion()
    {
        file_put_contents(vfsStream::url('config/version.yml'),'{ parameters: { version: { number: 1.2.3 } } }');

        $commandTester = $this->prepareCommand();

        $commandTester->execute(array());

        $this->assertRegExp('/\'1.2.3\'/', $commandTester->getDisplay());
    }
}

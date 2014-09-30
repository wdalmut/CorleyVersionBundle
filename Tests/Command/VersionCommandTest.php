<?php
namespace Corley\VersionBundle\Tests\Command;

use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Corley\VersionBundle\Command\VersionCommand;
use org\bovigo\vfs\vfsStream;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Dumper;

class VersionCommandTest extends \PHPUnit_Framework_TestCase
{
    private $kernel;
    private $parser;
    private $dumper;
    private $root;

    private function prepareCommand()
    {
        $this->kernel = $this->getMock('Symfony\\Component\\HttpKernel\\KernelInterface');
        $this->parser = new Parser();
        $this->dumper = new Dumper();

        $application = new Application($this->kernel);
        $application->add(new VersionCommand($this->parser, $this->dumper));

        $this->root = vfsStream::setup('config');

        $command = $application->find('corley:version:bump');
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
        $commandTester = $this->prepareCommand();

        $commandTester->execute(array("version" => "x.x.x"));

        $this->assertEquals('{ parameters: { version: { number: x.x.x } } }', file_get_contents(vfsStream::url('config/version.yml')));
        $this->assertRegExp('/Bumped version: \'x.x.x\'/', $commandTester->getDisplay());
    }
}

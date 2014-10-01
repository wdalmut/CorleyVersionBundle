<?php
namespace Corley\VersionBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Parser;

class ShowCommand extends Command
{
    private $parser;
    private $rootDir;

    public function __construct(Parser $parser)
    {
        parent::__construct(null);
        $this->parser = $parser;
    }

    public function setRootDir($rootDir)
    {
        $this->rootDir = $rootDir;
    }

    protected function getVersionFilePath()
    {
        return $this->rootDir . "/version.yml";
    }

    protected function configure()
    {
        $this
            ->setName("corley:version:show")
            ->setDefinition(array())
            ->setDescription("Show the actual version")
            ->setHelp(<<<EOF
Just show the actual app version

    <info>php app/console corley:version:show</info>
EOF
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filepath = $this->getVersionFilePath();
        if (file_exists($filepath)) {
            $yml = $this->parser->parse(file_get_contents($filepath));

            $versionNumber = $yml["parameters"]["version"]["number"];

            $output->writeln("The actual version is: '<info>{$versionNumber}</info>'");
        } else {
            $output->writeln("<warn>There are no releases.<warn>");
            $output->writeln("You can create a new release with: app/console corley:version:bump <version>");
        }
    }
}

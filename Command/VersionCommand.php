<?php
namespace Corley\VersionBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Dumper;

class VersionCommand extends Command
{
    private $parser;
    private $dumper;
    private $rootDir;

    public function __construct(Parser $parser, Dumper $dumper)
    {
        parent::__construct(null);
        $this->parser = $parser;
        $this->dumper = $dumper;
    }

    public function setRootDir($rootDir)
    {
        $this->rootDir = $rootDir;
    }

    protected function configure()
    {
        $this
            ->setName("corley:version")
            ->setDescription("Just bump a new version")
            ->setDefinition(array(
                new InputArgument('version', InputArgument::OPTIONAL, 'The new version label')
            ))
            ->setHelp(<<<EOF
Just update the version configuration file

    <info>php app/console corley:version x.x.x</info>
EOF
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $version = $input->getArgument("version");
        $filepath = $this->rootDir . '/version.yml';

        $values["parameters"]["version"]["number"] = $version;

        $yml = $this->dumper->dump($values);
        file_put_contents($this->rootDir  . '/version.yml', $yml);

        $output->writeln("Bumped version: '{$version}'");
    }

    /**
     * @see Command
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getArgument('version')) {
            $status = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please indicate the final version [x.x.x]',
                function ($version) {
                    if (!$version) {
                        throw new \InvalidArgumentException("You have to set a version number. Something like: '1.0.0'");
                    }
                    return $version;
                }
            );
            $input->setArgument('version', $status);
        }
    }
}

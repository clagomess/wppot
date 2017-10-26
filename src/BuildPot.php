<?php
namespace Src;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BuildPot extends Command
{
    protected function configure()
    {
        $this->setName('wppot:build');
        $this->setDescription("Build *.pot file");
        $this->addArgument('path', InputArgument::OPTIONAL, 'Path where is Wordpress Theme. Default: root');
        $this->addArgument('name', InputArgument::OPTIONAL, 'Name of *.pot file. Default: default.pot');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $path = $input->getArgument('path');

        $name = (empty($input->getArgument('name')) ? 'default.pot' : $name);
        $path = (empty($name) ? __DIR__ : $path);

        $wppot = new WpPot();
        $wppot->build($name, $path, $output);
    }
}
<?php

namespace Rohit\MyApp;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DataGenerate extends Command
{
  /**
   * {@inheritdoc}
   */
  protected function configure()
  {
    $this->setName('my-app');
    $this->setDescription('Generates the data dump');
    $this->addOption('format', 'f', InputOption::VALUE_OPTIONAL, '', 'Provide the default format');
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $format = $input->getOption('format')?: 'JSON';
    $output->writeln("Your data will be exported in: " . $format);
  }

}

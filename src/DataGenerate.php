<?php

namespace SfDataExport;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Exception\RuntimeException;


class DataGenerate extends Command
{
  /**
   * {@inheritdoc}
   */
  protected function configure()
  {
    $this->setName('sf_data_export');
    $this->setDescription('Exports the database into given format like xml/json.');
    $this->addOption('format', 'f', InputOption::VALUE_OPTIONAL, 'Provide the data export format - xml or json.', 'xml');
    $this->addOption('output_directory_path', 'o', InputOption::VALUE_OPTIONAL, 'Provide the output directory path', '/tmp/sf_data_export');
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output)
  {
    // Data format.
    $format = $input->getOption('format');
    // Output directory path.
    $output_directory = $input->getOption('output_directory_path');

    // Message.
    $output->writeln("Your data will be exported in " . $output_directory . " directory");

    $object = new WordpressQuery();
    if (!$object instanceof QueryInterface) {
      throw new RuntimeException('Class must implements the QueryInterface.');
    }
    $data = $object->getData();

    $encoders = [
      new XmlEncoder(),
      new JsonEncoder(),
    ];
    $normalizers = [
      new ObjectNormalizer(),
    ];
    $serializer = new Serializer($normalizers, $encoders);
    $fs = new Filesystem();

    if (!empty($data)) {
      // Create a progress bar.
      $progress = new ProgressBar($output);
      // Start and displays the progress bar.
      $progress->start();

      foreach ($data as $key => $item) {
        if (!empty($data[$key])) {
          $response = $serializer->serialize([$key => $item], $format);
          try {
            $file_name = $output_directory . '/' . $key . '.' .  $format;
            $fs->mkdir($output_directory);
            $fs->dumpFile($file_name, $response);
            $progress->advance();
            $output->writeln("\n  ");
          } catch (IOExceptionInterface $e) {
            echo "An error occurred while creating your directory at ". $e->getPath();
          }
        }
      }

      // ensure that the progress bar is at 100%
      $progress->finish();
    }
  }

}

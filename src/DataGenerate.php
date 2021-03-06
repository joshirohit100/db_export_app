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
    $this->addOption('rec_limit', 'rl', InputOption::VALUE_OPTIONAL, 'Provide limit for processing the records. It will create multiple files.', 10000);
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
    // Record limit.
    $record_limit = $input->getOption('rec_limit');

    // Message.
    $output->writeln("Your data will be exported in " . $output_directory . " directory");

    global $config;
    $data_src_class = $config['data_src_class'];
    $object = new $data_src_class();
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

      foreach ($data as $type => $sub_data) {
        if (!empty($data[$type])) {
          foreach ($sub_data as $sub_key => $sub_item) {

            $progress_count = count($sub_item) > $record_limit ? count($sub_item)/$record_limit : count($sub_item);
            $output->writeln("\n\nExporting '$sub_key' xmls : " . count($sub_item) . " records.");
            // Create a progress bar.
            $progress = new ProgressBar($output, $progress_count);
            // Start and displays the progress bar.
            $progress->start();

            $chunked_items = array_chunk($sub_item, $record_limit);
            foreach ($chunked_items as $key => $item) {
              $response = $serializer->serialize([$sub_key => $item], $format);
              try {
                $file_name = $output_directory . '/' . $sub_key . '_' . ($key+1) . '.' .  $format;
                $fs->mkdir($output_directory);
                $fs->dumpFile($file_name, $response);
                $progress->advance();
              } catch (IOExceptionInterface $e) {
                echo "An error occurred while creating your directory at ". $e->getPath();
              }
            }

            // ensure that the progress bar is at 100%
            $progress->finish();

          }
        }
      }

    }
  }

}

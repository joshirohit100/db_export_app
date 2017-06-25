<?php

namespace Rohit\MyApp;

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
    $format = $input->getOption('format')?: 'json';
    $output_directory = '/tmp/wordpress/';
    $output->writeln("Your data will be exported in: " . $format);
    $users = new WordpressQuery();
    $data = $users->getData();

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
      foreach ($data as $key => $item) {
        $response = $serializer->serialize([$key => $item], $format);
        try {
          $file_path = $output_directory . $key;
          $file_name = $file_path . '/' . $key . '.' .  $format;
          $fs->mkdir($file_path);
          $fs->dumpFile($file_name, $response);
        } catch (IOExceptionInterface $e) {
          echo "An error occurred while creating your directory at ". $e->getPath();
        }
      }
    }
  }

}
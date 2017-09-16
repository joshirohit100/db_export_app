<?php

$config = [];

$config['connection']['mysql'] = [
  'host' => '',
  'port' => '',
  'db' => '',
  'user' => '',
  'password' => '',
];

$config['data_src_class'] = '\SfDataExport\data_src\Wordpress';

if (file_exists('config.local.php')) {
  require_once 'config.local.php';
}

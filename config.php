<?php

$connection = [];

$connection['mysql'] = [
  'host' => '',
  'port' => '',
  'db' => '',
  'user' => '',
  'password' => '',
];

if (file_exists('config.local.php')) {
  require_once 'config.local.php';
}

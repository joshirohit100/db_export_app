<?php

require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/../config.php';

use SfDataExport\DataGenerate;
use Symfony\Component\Console\Application;

$command = new DataGenerate();

$application = new Application();
$application->add($command);
$application->run();

<?php

require __DIR__.'/../vendor/autoload.php';

use Rohit\MyApp\DataGenerate;
use Symfony\Component\Console\Application;

$command = new DataGenerate();

$application = new Application();
$application->add($command);
$application->run();

<?php

require __DIR__.'/../vendor/autoload.php';

use Rohit\MyApp\DataGenerate;
use Rohit\MyApp\MyContainer;
use Symfony\Component\Console\Application;

$container = new MyContainer();
$container->build();

$command = new DataGenerate();

$application = new Application();
$application->add($command);
$application->run();

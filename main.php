<?php
require __DIR__.'/vendor/autoload.php';

use Src\BuildPot;
use Symfony\Component\Console\Application;

$application = new Application();

$application->add(new BuildPot());

$application->run();
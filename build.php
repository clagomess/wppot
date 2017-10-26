#!/usr/bin/env php
<?php
ini_set("phar.readonly", 0);

// COMPILE
$phar = new Phar("wppot.phar");
$phar->startBuffering();

$defaultStub = $phar->createDefaultStub('main.php');

$phar->buildFromDirectory(dirname(__FILE__), '/(main\.php|vendor|src)/');

$stub = "#!/usr/bin/env php \n" . $defaultStub;

$phar->setStub($stub);

$phar->stopBuffering();
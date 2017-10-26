<?php
namespace Src;

use Phar;

class BuildPhar {
    public static function build(){
        ini_set("phar.readonly", 0);

        if(is_file('wppot.phar')){
            unlink('wppot.phar');
        }

        // COMPILE
        $phar = new Phar("wppot.phar");
        $phar->startBuffering();

        $defaultStub = $phar->createDefaultStub('main.php');

        $phar->buildFromDirectory(dirname(__DIR__), '/(main\.php|vendor|src)/');

        $stub = "#!/usr/bin/env php \n" . $defaultStub;

        $phar->setStub($stub);

        $phar->stopBuffering();
    }
}
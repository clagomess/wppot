<?php
namespace Src;

use Phar;

class BuildPhar {
    const pharname = "wppot.phar";

    public static function build(){
        ini_set("phar.readonly", 0);

        if(is_file(BuildPhar::pharname)){
            unlink(BuildPhar::pharname);
        }

        // COMPILE
        $phar = new Phar(BuildPhar::pharname);
        $phar->startBuffering();

        $defaultStub = $phar->createDefaultStub('main.php');

        $phar->buildFromDirectory(dirname(__DIR__), '/(main\.php|vendor|src)/');

        $stub = "#!/usr/bin/env php \n" . $defaultStub;

        $phar->setStub($stub);

        $phar->stopBuffering();
    }
}
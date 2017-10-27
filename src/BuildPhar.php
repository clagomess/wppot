<?php
namespace Src;

use Phar;

class BuildPhar {
    const PHARNAME = "wppot.phar";

    public static function build(){
        if(ini_get('phar.readonly') == 1) {
            echo 'Needs to disable "phar.readonly" on php.ini';
            return;
        }

        if(is_file(BuildPhar::PHARNAME)){
            unlink(BuildPhar::PHARNAME);
        }

        // COMPILE
        $phar = new Phar(BuildPhar::PHARNAME);
        $phar->startBuffering();

        $defaultStub = $phar->createDefaultStub('main.php');

        $phar->buildFromDirectory(dirname(__DIR__), '/(main\.php|vendor|src)/');

        $stub = "#!/usr/bin/env php \n" . $defaultStub;

        $phar->setStub($stub);

        $phar->stopBuffering();
    }
}
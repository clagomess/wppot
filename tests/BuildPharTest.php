<?php
namespace Tests;

use PHPUnit_Framework_TestCase;
use Src\BuildPhar;

class BuildPharTest extends PHPUnit_Framework_TestCase {
    public function testBuild(){
        if(ini_get('phar.readonly') != 1) {
            BuildPhar::build();

            $this->assertFileExists(BuildPhar::PHARNAME);

            $phar = new \Phar(BuildPhar::PHARNAME);

            $this->assertTrue($phar->valid());
            $this->assertEquals('main.php', $phar->getFilename());
        }
    }
}
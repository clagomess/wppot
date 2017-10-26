<?php
namespace Tests;

use PHPUnit_Framework_TestCase;
use Src\WpPot;
use Symfony\Component\Console\Output\ConsoleOutput;

class WpPotTest extends PHPUnit_Framework_TestCase {
    public function testBase(){
        $this->assertTrue(true);
    }

    public function testReadPhpFiles(){
        $wppot = new WpPot();
        $arFiles = $wppot->readPhpFiles("assets", new ConsoleOutput());

        $this->assertTrue(count($arFiles) == 1);
        $this->assertArrayHasKey('filename', current($arFiles));
        $this->assertArrayHasKey('filepath', current($arFiles));
        $this->assertEquals('index.php', current($arFiles)['filename']);
        $this->assertEquals('assets'.DIRECTORY_SEPARATOR.'index.php', current($arFiles)['filepath']);
    }
}
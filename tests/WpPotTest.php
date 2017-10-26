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

    public function testBuild(){
        $wppot = new WpPot();
        $wppot->build('default.pot', 'assets', new ConsoleOutput());

        $potfile = 'assets'.DIRECTORY_SEPARATOR.'languages'.DIRECTORY_SEPARATOR.'default.pot';

        $this->assertTrue(file_exists($potfile));

        $fp = fopen($potfile, 'r');
        $content = fread($fp, filesize($potfile));
        fclose($fp);

        $this->assertContains('index.php:2', $content);
        $this->assertContains('msgid "foo"', $content);
    }
}
<?php
namespace Tests;

use Symfony\Component\Console\Application;
use PHPUnit_Framework_TestCase;
use Src\BuildPot;
use Symfony\Component\Console\Tester\CommandTester;

class BuildPotTest extends PHPUnit_Framework_TestCase {
    public function testExecute(){
        $taskname = 'build';

        $app = new Application();
        $app->add(new BuildPot());

        $cmd = $app->find($taskname);

        $this->assertEquals($taskname, $cmd->getName());

        $cmdTester = new CommandTester($cmd);
        $cmdTester->execute(array(
            'command' => $cmd->getName(),
            'path' => 'assets',
            'name' => 'test.pot'
        ));

        $output = $cmdTester->getDisplay();

        $this->assertContains('test.pot', $output);
        $this->assertContains('Theme Path: assets', $output);
        $this->assertContains('index.php', $output);
        $this->assertContains('Building POT', $output);
    }
}
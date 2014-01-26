<?php

use Fedeisas\LaravelJsRoutes\Commands\RoutesJavascriptCommand;
use Symfony\Component\Console\Tester\CommandTester;
use Mockery as m;

class RoutesJavascriptCommandTest extends PHPUnit_Framework_TestCase
{

    public function tearDown()
    {
        m::close();
    }

    public function testGeneratesJavascript()
    {
        $generator = m::mock('Fedeisas\LaravelJsRoutes\Generators\RoutesJavascriptGenerator');

        $generator->shouldReceive('make')
            ->once()
            ->with('/foo/bar', 'routes.js', array('filter' => null, 'object' => 'Router'))
            ->andReturn(true);

        $command = new RoutesJavascriptCommand($generator, $this->mockBasePath());

        $tester = new CommandTester($command);
        $tester->execute(array());

        $this->assertEquals("Created /foo/bar/routes.js\n", $tester->getDisplay());
    }

    public function testCanSetCustomPathAndCustomObject()
    {
        $generator = m::mock('Fedeisas\LaravelJsRoutes\Generators\RoutesJavascriptGenerator');

        $generator->shouldReceive('make')
            ->once()
            ->with('assets/js', 'myRoutes.js', array('filter' => null, 'object' => 'Router'))
            ->andReturn(true);

        $command = new RoutesJavascriptCommand($generator, $this->mockBasePath());

        $tester = new CommandTester($command);
        $tester->execute(array('name' => 'myRoutes.js', '--path' => 'assets/js'));

        $this->assertEquals("Created assets/js/myRoutes.js\n", $tester->getDisplay());
    }

    private function mockBasePath()
    {
        $basePath = m::mock('Fedeisas\LaravelJsRoutes\BasePath')->makePartial();
        $basePath->shouldReceive('get')->andReturn('/foo/bar');
        return $basePath;
    }
}

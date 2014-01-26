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
        $gen = m::mock('Fedeisas\LaravelJsRoutes\Generators\RoutesJavascriptGenerator');

        $gen->shouldReceive('make')
            ->once()
            ->with('/routes.js')
            ->andReturn(true);

        $command = new RoutesJavascriptCommand($gen);

        $tester = new CommandTester($command);
        $tester->execute(['name' => 'routes.js']);

        $this->assertEquals("Created /routes.js\n", $tester->getDisplay());
    }

    public function testCanSetCustomPath()
    {
        $gen = m::mock('Fedeisas\LaravelJsRoutes\Generators\RoutesJavascriptGenerator');

        $gen->shouldReceive('make')
            ->once()
            ->with('assets/js/myRoutes.js')
            ->andReturn(true);

        $command = new RoutesJavascriptCommand($gen);

        $tester = new CommandTester($command);
        $tester->execute(['name' => 'myRoutes.js', '--path' => 'assets/js']);

        $this->assertEquals("Created assets/js/myRoutes.js\n", $tester->getDisplay());
    }
}

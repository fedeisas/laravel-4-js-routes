<?php

use Fedeisas\LaravelJsRoutes\Commands\RoutesJavascriptCommand;
use Symfony\Component\Console\Tester\CommandTester;
use Mockery as m;

class RoutesJavascriptCommandTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $app = m::mock('Application')
                ->shouldReceive('make')
                ->with('path.base')
                ->andReturn('/foo/bar')
                ->mock();
        Illuminate\Support\Facades\Facade::setFacadeApplication($app);
    }

    public function tearDown()
    {
        m::close();
    }

    /** @test **/
    public function it_generated_javascript()
    {
        $generator = m::mock('Fedeisas\LaravelJsRoutes\Generators\RoutesJavascriptGenerator');

        $generator->shouldReceive('make')
            ->once()
            ->with('/foo/bar', 'routes.js', array('filter' => null, 'object' => 'Router', 'prefix' => null))
            ->andReturn(true);

        $command = new RoutesJavascriptCommand($generator);

        $tester = new CommandTester($command);
        $tester->execute(array());

        $this->assertEquals("Created /foo/bar/routes.js\n", $tester->getDisplay());
    }

    /** @test **/
    public function it_can_set_custom_path_and_custom_object_and_prefix()
    {
        $generator = m::mock('Fedeisas\LaravelJsRoutes\Generators\RoutesJavascriptGenerator');

        $generator->shouldReceive('make')
            ->once()
            ->with('assets/js', 'myRoutes.js', array('filter' => null, 'object' => 'MyRouter', 'prefix' => 'prefix/'))
            ->andReturn(true);

        $command = new RoutesJavascriptCommand($generator);

        $tester = new CommandTester($command);
        $tester->execute(array('name' => 'myRoutes.js', '--path' => 'assets/js', '--object' => 'MyRouter', '--prefix' => 'prefix/'));

        $this->assertEquals("Created assets/js/myRoutes.js\n", $tester->getDisplay());
    }

    /** @test **/
    public function it_fails_on_unexistent_path()
    {
        $generator = m::mock('Fedeisas\LaravelJsRoutes\Generators\RoutesJavascriptGenerator');

        $generator->shouldReceive('make')
            ->once()
            ->with('unexistent/path', 'myRoutes.js', array('filter' => null, 'object' => 'Router', 'prefix' => null))
            ->andReturn(false);

        $command = new RoutesJavascriptCommand($generator);

        $tester = new CommandTester($command);
        $tester->execute(array('name' => 'myRoutes.js', '--path' => 'unexistent/path'));

        $this->assertEquals("Could not create unexistent/path/myRoutes.js\n", $tester->getDisplay());
    }
}

<?php

use Fedeisas\LaravelJsRoutes\Generators\RoutesJavascriptGenerator;
use Mockery as m;

class RoutesJavascriptGeneratorTest extends PHPUnit_Framework_TestCase
{

    protected static $templatesDir;

    public function __construct()
    {
        static::$templatesDir = __DIR__.'/../../src/Way/Generators/Generators/templates';
    }

    public function tearDown()
    {
        m::close();
    }

    protected function getRouter()
    {
        $router = new Illuminate\Routing\Router(new Illuminate\Events\Dispatcher);
        $router->get('user/{id}', array('as' => 'user.show', 'uses' => function ($id) {
            return $id;
        }));
        $router->post('user', array('as' => 'user.store', 'before' => 'js-routable', 'uses' => function ($id) {
            return $id;
        }));
        $router->get('/user/{id}/edit', array('as' => 'user.edit', 'before' => 'js-routable', 'uses' => function ($id) {
            return $id;
        }));
        $router->get('/unnamed_route', array('uses' => function ($id) {
            return $id;
        }));
        return $router;
    }

    /** @test **/
    public function it_can_generate_javascript()
    {
        $file = m::mock('Illuminate\Filesystem\Filesystem')->makePartial();

        $file->shouldReceive('isWritable')
             ->once()
             ->andReturn(true);

        $file->shouldReceive('put')
             ->once()
             ->with('/foo/bar/routes.js', file_get_contents(__DIR__.'/stubs/javascript.txt'));

        $generator = new RoutesJavascriptGenerator($file, $this->getRouter());
        $generator->make('/foo/bar', 'routes.js', array('filter' => null, 'object' => 'Router'));
    }

    /** @test **/
    public function it_can_generate_javascript_with_custom_object()
    {
        $file = m::mock('Illuminate\Filesystem\Filesystem')->makePartial();

        $file->shouldReceive('isWritable')
             ->once()
             ->andReturn(true);

        $file->shouldReceive('put')
             ->once()
             ->with('/foo/bar/routes.js', file_get_contents(__DIR__.'/stubs/custom-object.txt'));

        $generator = new RoutesJavascriptGenerator($file, $this->getRouter());
        $generator->make('/foo/bar', 'routes.js', array('filter' => null, 'object' => 'MyRouter'));
    }

    /** @test **/
    public function it_can_generate_javascript_with_custom_filter()
    {
        $file = m::mock('Illuminate\Filesystem\Filesystem')->makePartial();

        $file->shouldReceive('isWritable')
             ->once()
             ->andReturn(true);

        $file->shouldReceive('put')
             ->once()
             ->with('/foo/bar/routes.js', file_get_contents(__DIR__.'/stubs/custom-filter.txt'));

        $generator = new RoutesJavascriptGenerator($file, $this->getRouter());
        $generator->make('/foo/bar', 'routes.js', array('filter' => 'js-routable', 'object' => 'Router'));
    }

    /** @test **/
    public function if_fails_on_non_writable_path()
    {
        $file = m::mock('Illuminate\Filesystem\Filesystem')->makePartial();

        $file->shouldReceive('isWritable')
             ->once()
             ->andReturn(false);

        $generator = new RoutesJavascriptGenerator($file, $this->getRouter());
        $output = $generator->make('/foo/bar', 'routes.js', array('filter' => 'js-routable', 'object' => 'Router'));

        $this->assertFalse($output);
    }
}

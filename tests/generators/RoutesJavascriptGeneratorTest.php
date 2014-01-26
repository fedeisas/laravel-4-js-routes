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
        $router->get('user/{id}', ['as' => 'user.show', function ($id) {
            return $id;
        }]);
        return $router;
    }

    public function testCanGenerateJavascript()
    {
        $file = m::mock('Illuminate\Filesystem\Filesystem')->makePartial();

        $file->shouldReceive('isWritable')
             ->once()
             ->andReturn(true);

        $file->shouldReceive('put')
             ->once()
             ->with('routes.js', file_get_contents(__DIR__.'/stubs/javascript.txt'));

        $generator = new RoutesJavascriptGenerator($file, $this->getRouter());
        $generator->make('routes.js');
    }
}

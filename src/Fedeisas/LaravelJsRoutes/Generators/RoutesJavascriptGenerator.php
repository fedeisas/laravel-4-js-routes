<?php namespace Fedeisas\LaravelJsRoutes\Generators;

use Illuminate\Filesystem\Filesystem as File;
use Illuminate\Routing\Router;
use Illuminate\Routing\Route;

class RoutesJavascriptGenerator
{

    /**
     * File system instance
     * @var Illuminate\Filesystem\Filesystem
     */
    protected $file;

    /**
     * Router instance
     * @var Illuminate\Routing\Router
     */
    protected $router;

    /**
     * Clean routes
     * @var array
     */
    protected $routes;

    /**
     * Parsed routes
     * @var array
     */
    protected $parsedRoutes;

    public function __construct(File $file, Router $router)
    {
        $this->file = $file;
        $this->router = $router;
        $this->routes = $router->getRoutes();

        foreach ($this->routes as $route) {
            $routeInfo = $this->getRouteInformation($route);
            if ($routeInfo) {
                $this->parsedRoutes[] = $routeInfo;
            }
        }
    }

    /**
     * Compile routes template and generate
     *
     * @param string $path
     * @param string $name
     * @return boolean
     */
    public function make($path, $name)
    {
        $template = $this->file->get(__DIR__ . '/templates/javascript.txt');
        $template = str_replace('{{ routes }}', json_encode(array_filter($this->parsedRoutes)), $template);

        if ($this->file->isWritable($path)) {
            $filename = $path . '/' . $name;
            return $this->file->put($filename, $template) !== false;
        }

        return false;
    }

    /**
     * Get the route information for a given route.
     *
     * @param string $name
     * @param \Illuminate\Routing\Route $route
     * @return array
     */
    protected function getRouteInformation(Route $route)
    {
        if ($route->getName()) {
            return array(
                'uri'    => $route->uri(),
                'name'   => $route->getName()
            );
        }
    }
}

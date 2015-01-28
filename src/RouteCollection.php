<?php
namespace jlandfried\Router;

class RouteCollection implements RouteCollectionInterface {
    private $method_route_map;

    public function __construct() {
        $this->method_route_map = [];
    }

    /**
     * {@inheritdoc}
    */
    public function add(RouteInterface $route) {
        $methods = $route->getMethods();
        foreach ($methods as $method) {
            $this->addMethodRoute($method, $route);
        }
    }

    /**
    * {@inheritdoc}
    */
    public function getRoute($method, $pattern) {
        if (isset($this->method_route_map[$method][$pattern])) {
            return $this->method_route_map[$method][$pattern];
        }
        throw new \Exception("Pattern '$pattern' doesn't match any defined '$method' routes.");
    }

    /**
     * Add a route to the method-sorted collection.
     *
     * @param string $method
     * @param string $method
     * @param RouteInterface $route
     */
    protected function addMethodRoute($method, RouteInterface $route) {
        $this->method_route_map[strtolower($method)][$route->getPattern()] = $route;
    }
}
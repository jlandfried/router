<?php
namespace jlandfried\Router;

class RouteCollection implements RouteCollectionInterface {
    private $method_route_map;
    private $named_route_map;

    public function __construct() {
        $this->method_route_map = [];
        $this->named_route_map = [];
    }

    /**
     * {@inheritdoc}
    */
    public function add(RouteInterface $route) {
        $methods = $route->getMethods();
        foreach ($methods as $method) {
            $this->addMethodRoute($method, $route);
        }

        $name = $route->getName();
        if ($name) {
            foreach ($methods as $method) {
                $this->addNamedRoute($name, $method, $route);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getNamedRoute($name, $method) {
        $method = strtolower($method);
        if (isset($this->named_route_map[$name][$method])) {
            return $this->named_route_map[$name][$method];
        }
        throw new \Exception("No route with name '$name' and method '$method' exists.");
    }

    /**
    * Add a route to the method-sorted collection.
    *
    * @param string $method
    * @param RouteInterface $route
    */
    protected function addMethodRoute($method, RouteInterface $route) {
        $method = strtolower($method);
        $this->method_route_map[$method][] = $route;
    }

    /**
     * Add a route to the method-sorted collection.
     *
     * @param string $name
     * @param string $method
     * @param RouteInterface $route
     */
    protected function addNamedRoute($name, $method, RouteInterface $route) {
        $method = strtolower($method);
        $this->named_route_map[$name][$method] = $route;
    }

    /**
     * Get routes using a certain method.
     *
     * @param string $method
     * @return array
     */
    public function getMethodRoutes($method) {
        if (isset($this->method_route_map[$method])) {
            return $this->method_route_map[$method];
        }
        return [];
    }
}

<?php
namespace jlandfried\Router;

interface RouteCollectionInterface {
    /**
     * Add a route to the list.
     *
     * @param RouteInterface $route
     */
    public function add(RouteInterface $route);

    /**
     * Get routes using a certain method.
     *
     * @param string $method
     * @return array
     */
    public function getMethodRoutes($method);

    /**
     * Retrieve a route by its name
     *
     * @param $name
     * @param $method
     * @return RouteInterface
     * @throws \Exception
     */
    public function getNamedRoute($name, $method);
}

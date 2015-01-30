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
     * Search stored routes and return one that matches the current request.
     *
     * @param string $method
     * @param string $uri
     * @return mixed
     * @throws \Exception
     */
    public function getCurrentRoute($method, $uri);

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

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
     * Get a route from storage.
     *
     * @param string $method
     * @param string $pattern
     * @return mixed
     * @throws \Exception
     */
    public function getRoute($method, $pattern);
}

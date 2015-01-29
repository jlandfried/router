<?php

namespace jlandfried\Router;

interface RouteInterface {
    /**
     * @param string|array $methods
     *   The HTTP method(s) that this route can be used for.
     * @param string $pattern
     *   String defining what paths this route should work with.
     * @param mixed $handler
     *   An arbitrary value that indicates what should happen when this route
     *   is matched.
     * @param string $name
     *   A name that can be used for reverse route look-ups.
     */
    public function __construct($methods, $pattern, $handler, $name = NULL);

    /**
     * Get an array of allowed methods.
     *
     * @return array
     */
    public function getMethods();

    /**
     * Get name of route.
     *
     * @return null|string
     */
    public function getName();

    /**
     * Get the pattern that this route matches.
     *
     * @return string
     */
    public function getPattern();

    /**
     * Get parameters from a route path.
     *
     * @param bool $with_delimiter
     */
    public function getParameters($with_delimiter = FALSE);

    /**
     * Check if a a provided uri matches the route pattern.
     *
     * @param string $uri
     * @return bool
     */
    public function match($uri);
}

<?php

namespace jlandfried\Router;

interface RouteMatcherInterface {
    public function __construct(RouteInterface $route, $uri);


    public function match();

    /**
     * @param bool $with_delimiter
     *
     * @returns array
     */
    public function getParams($with_delimiter = false);
}
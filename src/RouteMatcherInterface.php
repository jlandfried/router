<?php

namespace jlandfried\Router;

interface RouteMatcherInterface {
    public function __construct(RouteCollectionInterface $collection);

    /**
     * @param RouteMatcherParamBagInterface $params
     * @return mixed
     */
    public function match(RouteMatcherParamBagInterface $params);

    /**
     * @param RouteInterface $route;
     * @param bool $with_delimiter
     *
     * @returns array
     */
    public function getParams(RouteInterface $route, $with_delimiter = false);
}
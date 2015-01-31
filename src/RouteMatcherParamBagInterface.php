<?php

namespace jlandfried\Router;

interface RouteMatcherParamBagInterface {

    public function __construct(array $params);

    /**
     * Generic getter.
     *
     * @param $param
     * @return mixed
     * @throws \Exception
     */
    public function get($param);
}
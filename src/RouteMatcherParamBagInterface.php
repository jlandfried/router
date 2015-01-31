<?php

namespace jlandfried\Router;

interface RouteMatcherParamBagInterface {
    public function __construct(array $params);
    public function get($param);
}
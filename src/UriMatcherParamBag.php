<?php

namespace jlandfried\Router;

class UriMatcherParamBag implements RouteMatcherParamBagInterface {
    protected $method;
    protected $uri;

    public function __construct(array $params) {
        if (!isset($params['method'])) {
            throw new \Exception("No method parameter included");
        }
        if (!isset($params['uri'])) {
            throw new \Exception("No uri parameter included");
        }
        $this->method = $params['method'];
        $this->uri = $params['uri'];
    }

    /**
     * {@inheritdoc}
     */
    public function get($param) {
        if (!isset($this->{$param})) {
            throw new \Exception("Invalid parameter '$param'");
        }
        return $this->{$param};
    }
}
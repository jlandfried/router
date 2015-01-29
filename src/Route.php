<?php

namespace jlandfried\Router;

class Route implements RouteInterface {
    protected $methods;
    protected $pattern;
    protected $handler;
    protected $name;
    protected $parameters;

    /**
     * {@inheritdoc}
     */
    public function __construct($methods, $pattern, $handler, $name = NULL) {
        $methods = is_array($methods) ? $methods : [$methods];
        $this->methods = array_map('strtolower', $methods);
        $this->pattern = $pattern;
        $this->handler = $handler;

        if (!is_null($name) && !is_string($name)) {
            throw new \Exception('Named routes must use a string for their name.');
        }
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function getMethods() {
        return $this->methods;
    }

    /**
     * {@inheritdoc}
     */
    public function getPattern() {
        return $this->pattern;
    }

    /**
     * {@inheritdoc}
     */
    public function getName() {
        return $this->name;
    }
}

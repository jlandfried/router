<?php

namespace jlandfried\Router;

class Route implements RouteInterface {
    protected $methods;
    protected $pattern;
    protected $handler;
    protected $name;

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
     * Get methods that this route matches.
     */
    public function getMethods() {
        return $this->methods;
    }

    /**
     * Get the pattern that this route matches.
     *
     * @return string
     */
    public function getPattern() {
        return $this->pattern;
    }

    /**
     * Get route name.
     *
     * @return null|string
     */
    public function getName() {
        return $this->name;
    }
}

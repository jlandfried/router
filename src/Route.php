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

        // Extract parameters from route pattern.
        $parameters = [];
        preg_match_all('#{(.*?)}#', $this->getPattern(), $parameters);
        $this->parameters = $parameters;

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

    /**
     * {@inheritdoc}
     */
    public function getParameters($with_delimiter = FALSE) {
        return $with_delimiter ? $this->parameters[0] : $this->parameters[1];
    }

    /**
     * {@inheritdoc}
     */
    public function match($uri) {
        $pattern = $this->getPattern();
        if ($uri == $pattern) { return true; }

        // Break out the static portions of the pattern.
        $static_portions = preg_split('#{(.*?)}#', $pattern);
        if (count($static_portions) == 1) { return false; }

        $variables = [];
        foreach($static_portions as $key => $portion) {
            $parts = $portion != '' ? explode($portion, $uri) : ['',$uri];
            try {
                if (isset($static_portions[$key + 1]) && $next_portion = $static_portions[$key + 1]) {
                    $variables[] = substr($parts[1], 0, strpos($parts[1], $next_portion));
                }

                // If the variable is at the end.
                elseif (count($parts) === 2 && $parts[1] != '') {
                    $variables[] = $parts[1];
                }
            }
            catch (\Exception $e) {
                return false;
            }
        }

        $static_uri_text = str_replace($variables, '', $uri);
        $static_pattern_text = preg_replace('#{(.*?)}#', '', $pattern);
        return $static_pattern_text === $static_uri_text;
    }
}

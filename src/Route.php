<?php

namespace jlandfried\Router;

class Route implements RouteInterface {
    const DELIMITER_REGEX = '#{(.*?)}#';

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
        preg_match_all(self::DELIMITER_REGEX, $this->getPattern(), $parameters);
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
        // Break out the static portions of the pattern.
        $static_portions = $this->getStaticPatternParts();

        if (count($static_portions) === 1) {
            return $this->matchStaticPattern($uri);
        }
        return $this->matchDynamicPattern($uri, $static_portions);
    }

    /**
     * Get parts of a pattern that are not variables.
     *
     * @return array
     */
    protected function getStaticPatternParts() {
        return preg_split(self::DELIMITER_REGEX, $this->getPattern());
    }

    /**
     * Check if a uri matches the pattern.
     *
     * @param $uri
     * @return bool
     */
    protected function matchStaticPattern($uri) {
        return $uri === $this->getPattern();
    }

    /**
     * Check if a provided uri matches a pre-split dynamic uri.
     *
     * @param string $uri
     * @param array $static_portions
     * @return bool
     */
    protected function matchDynamicPattern($uri, array $static_portions) {
        // Array index exceptions indicate that a route does not match.
        try {
            $variables = [];
            foreach ($static_portions as $key => $portion) {
                $parts = $portion != '' ? explode($portion, $uri) : ['', $uri];
                if (isset($static_portions[$key + 1]) && $next_portion = $static_portions[$key + 1]) {
                    $variables[] = substr($parts[1], 0, strpos($parts[1], $next_portion));
                }
                // If the variable is at the end.
                elseif (count($parts) === 2 && $parts[1] != '') {
                    $variables[] = $parts[1];
                }
            }
            return str_replace($variables, '', $uri) === implode($static_portions);
        }
        catch (\Exception $e) {
            return false;
        }
    }
}

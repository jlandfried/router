<?php
namespace jlandfried\Router;

class RouteMatcher implements RouteMatcherInterface {
    const DELIMITER_REGEX = '#{(.*?)}#';

    protected $route;
    protected $uri;
    protected $parameters;
    protected $matches = false;

    public function __construct(RouteInterface $route, $uri) {
        $this->route = $route;
        $this->uri = $uri;

        // Extract parameters from route pattern.
        $parameters = [];
        preg_match_all(self::DELIMITER_REGEX, $this->route->getPattern(), $parameters);
        $this->parameters = $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function match() {
        // Break out the static portions of the pattern.
        $static_portions = $this->getStaticPatternParts();

        if (count($static_portions) === 1) {
            return $this->matchStaticPattern();
        }
        return $this->matchDynamicPattern($static_portions);
    }

    /**
     * {@inheritdoc}
     */
    public function getParams($with_delimiter = false) {
        return $with_delimiter ? $this->parameters[0] : $this->parameters[1];
    }

    /**
     * Check if a uri matches the pattern without any variable replacement.
     *
     * @return bool
     */
    protected function matchStaticPattern() {
        return $this->uri === $this->route->getPattern();
    }

    /**
     * Check if a provided uri matches a pre-split dynamic uri.
     *
     * @param array $static_portions
     * @return bool
     */
    protected function matchDynamicPattern(array $static_portions) {
        $variables = [];
        foreach ($static_portions as $key => $portion) {
            $parts = $portion != '' ? explode($portion, $this->uri) : ['', $this->uri];
            if (!isset($parts[1])) {
                return false;
            }
            elseif ($next_portion = $this->getNextPatternPortion($static_portions, $key)) {
                $variables[] = substr($parts[1], 0, strpos($parts[1], $next_portion));
            }
            // If the variable is at the end.
            elseif ($this->analyzingEnd($parts)) {
                $variables[] = $parts[1];
            }
        }
        return str_replace($variables, '', $this->uri) === implode($static_portions);
    }

    /**
     * Check if there are two portions of a pattern that can be compared.
     *
     * @param $portions
     * @param $key
     * @return null
     */
    protected function getNextPatternPortion($portions, $key) {
        if (isset($portions[$key + 1]) && $next_portion = $portions[$key + 1]) {
            return $next_portion;
        }
        return NULL;
    }

    /**
     * Check if analyzing end of a pattern.
     *
     * @param $pattern_parts
     * @return bool
     */
    protected function analyzingEnd($pattern_parts) {
        return count($pattern_parts) === 2 && $pattern_parts[1] != '';
    }

    /**
     * Get parts of a pattern that are not variables.
     *
     * @return array
     */
    protected function getStaticPatternParts() {
        return preg_split(self::DELIMITER_REGEX, $this->route->getPattern());
    }
}

<?php

namespace jlandfried\Router;

class UriMatcher implements RouteMatcherInterface {
    const DELIMITER_REGEX = '#{(.*?)}#';

    /**
     * @var RouteCollectionInterface
     */
    protected $collection;

    public function __construct(RouteCollectionInterface $collection) {
        $this->collection = $collection;
    }

    public function match(RouteMatcherParamBagInterface $bag) {
        $uri = $bag->get('uri');
        $routes = $this->collection->getMethodRoutes($bag->get('method'));
        /** @var RouteInterface $route */
        foreach($routes as $route) {
            if ($this->matchStaticPattern($route, $uri)) {
                return true;
            }
            elseif ($this->matchDynamicPattern($route, $uri)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param RouteInterface $route
     * @param bool $with_delimiter
     *
     * @returns array
     */
    public function getParams(RouteInterface $route, $with_delimiter = FALSE) {
        $parameters = [];
        preg_match_all(self::DELIMITER_REGEX, $route->getPattern(), $parameters);
        return $with_delimiter ? $parameters[0] : $parameters[1];
    }

    /**
     * Get static portions of pattern.
     *
     * @param RouteInterface $route
     * @return array
     */
    protected function getStaticPortions(RouteInterface $route) {
        return preg_split(self::DELIMITER_REGEX, $route->getPattern());
    }

    /**
     * Match provided uri against dynamic pattern.
     *
     * @param RouteInterface $route
     * @param string $uri
     * @return bool
     */
    protected function matchDynamicPattern(RouteInterface $route, $uri) {
        $static_portions = $this->getStaticPortions($route);
        $variables = [];
        foreach ($static_portions as $key => $portion) {
            $parts = $portion != '' ? explode($portion, $uri) : ['', $uri];
            if (!isset($parts[1])) {
                return false;
            }
            elseif ($next_portion = $this->getNextPatternPortion($static_portions, $key)) {
                $variables[] = substr($parts[1], 0, strpos($parts[1], $next_portion));
            }
            elseif ($this->analyzingEnd($parts)) {
                $variables[] = $parts[1];
            }
        }
        return str_replace($variables, '', $uri) === implode($static_portions);
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
     * @param RouteInterface $route
     * @param $uri
     * @return bool
     */
    protected function matchStaticPattern(RouteInterface $route, $uri) {
        return $route->getPattern() === $uri;
    }
}
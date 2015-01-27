<?php

namespace jiff\Router;

interface RouteInterface {
  /**
   * @param string|array $methods
   *   The HTTP method(s) that this route can be used for.
   * @param $pattern
   *   RegExp defining what paths this route should work with.
   * @param callable $handler
   *   An arbitrary value that indicates what should happen when this route
   *   is matched.
   */
  public function __construct($methods, $pattern, $handler);
}
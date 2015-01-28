<?php

namespace jlandfried\Router;

class Route implements RouteInterface {
  protected $methods;
  protected $pattern;
  protected $handler;

  /**
   * {@inheritdoc}
   */
  public function __construct($methods, $pattern, $handler) {
    $methods = is_array($methods) ? $methods : [$methods];
    $this->methods = array_map('strtolower', $methods);
    $this->pattern = $pattern;
    $this->handler = $handler;
  }

  /**
   * Get methods that this route matches.
   */
  public function getMethods() {
    return $this->methods;
  }

  public function getPattern() {
    return $this->pattern;
  }
}

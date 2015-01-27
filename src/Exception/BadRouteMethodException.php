<?php
namespace jiff\Router\Exception;

class BadRouteMethodException extends \Exception {
  private $method;

  public function __construct($method) {
    $this->method = $method;
    parent::__construct(strtr('Method "@method" is not allowed', ['@method' => $method]));
  }
}
<?php
namespace jiff\Router;

use SplObjectStorage;
use jiff\Router\Exception\BadRouteMethodException;

class RouteCollection extends SplObjectStorage implements RouteCollectionInterface {
  /**
   * Accepted HTTP methods for this route
   * @var array
   */
  private $methods = array('GET', 'POST', 'PUT', 'DELETE');

  /**
   * {@inheritdoc}
   */
  public function add($method, $callable, $id = NULL) {
    if ($id == NULL) {
      $id = $callable;
    }

    if (in_array($method, $this->methods)) {
      $this->attach($method, $id, $callable);
    }
    else {
     throw new BadRouteMethodException($method);
    }

    $this->attach($method, $id, $callable);
  }



  /**
   * {@inheritdoc}
   */
  public function remove($name) {
    return 'testRemove';
  }

}
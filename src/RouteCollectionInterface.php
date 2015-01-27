<?php
namespace jiff\Router;

interface RouteCollectionInterface {

  /**
   * Add a route to the list.
   *
   * @param string $method
   * @param $callable
   * @param null $id
   * @return mixed
   */
  public function add($method, $callable, $id = NULL);

  /**
   * Remove a route from the list.
   *
   * @param $name
   * @return mixed
   */
  public function remove($name);

}
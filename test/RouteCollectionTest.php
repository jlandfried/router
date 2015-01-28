<?php
namespace jlandfried\Router\Test;

use jlandfried\Router\Route;
use jlandfried\Router\RouteCollection;
use PHPUnit_Framework_TestCase;

/**
 * @group router
 */
class RouterTest extends PHPUnit_Framework_TestCase {
    private $router;
    private $singleMethodRoute;
    private $multiMethodRoute;

    public function setUp() {
        $this->router = new RouteCollection();
        $this->singleMethodRoute = new Route('get', 'home', 'some--handler');
        $this->multiMethodRoute = new Route(['get', 'post'], 'home', 'some--handler');
    }

    /**
     * Routes that get added with a single method should be accessible.
     */
    public function testAddSingleMethod() {
      $this->router->add($this->singleMethodRoute);
      $route = $this->router->getRoute('get', 'home');
      $this->assertEquals($this->singleMethodRoute, $route);
    }

    /**
     * Routes that get added with multiple methods should be accessible
     * from all methods.
     */
    public function testAddMultipleMethods() {
        $route = $this->multiMethodRoute;
        $router = $this->router;
        $this->router->add($route);
        foreach ($route->getMethods() as $method) {
            $this->assertEquals($route, $router->getRoute($method, $route->getPattern()));
        }
    }

    /**
     * Routes that get added with a single method should not be accessible
     * using other methods.
     *
     * @expectedException \Exception
     * @expectedExceptionMessage Pattern 'home' doesn't match any defined 'post' routes.
     */
    public function testIncorrectSingleMethodLookup() {
      $this->router->add($this->singleMethodRoute);
      $this->router->getRoute('post', 'home');
    }

    /**
     * Routes that get added with a single method should not be accessible
     * using other methods.
     *
     * @expectedException \Exception
     * @expectedExceptionMessage Pattern 'home' doesn't match any defined 'patch' routes.
     */
    public function testIncorrectMultiMethodLookup() {
        $this->router->add($this->singleMethodRoute);
        $this->router->getRoute('patch', 'home');
    }
}
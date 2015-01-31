<?php
namespace jlandfried\Router\Test;

use jlandfried\Router\Route;
use jlandfried\Router\RouteInterface;
use jlandfried\Router\RouteCollection;
use jlandfried\Router\RouteCollectionInterface;
use jlandfried\Router\UriMatcher;
use PHPUnit_Framework_TestCase;

/**
 * @group router
 */
class RouterTest extends PHPUnit_Framework_TestCase {
    /**
     * @var RouteCollectionInterface
     */
    private $router;

    /**
     * @var RouteInterface
     */
    private $singleMethodRoute;

    /**
     * @var RouteInterface
     */
    private $multiMethodRoute;

    /**
     * @var UriMatcher
     */
    private $uriMatcher;

    public function setUp() {
        $this->router = new RouteCollection();
        $this->singleMethodRoute = new Route('get', 'home', 'some--handler', 'single.method.route');
        $this->multiMethodRoute = new Route(['get', 'post'], 'home', 'some--other--handler', 'multiple.method.route');
        $this->uriMatcher = new UriMatcher($this->router);
    }

    /**
     * Routes that get added with a single method should be accessible.
     */
    public function testAddSingleMethod() {
        $this->router->add($this->singleMethodRoute);
        $routes = $this->router->getMethodRoutes('get');
        $this->assertEquals($this->singleMethodRoute, $routes[0]);
    }

    /**
     * Routes that get added with multiple methods should be accessible
     * from all methods.
     */
    public function testAddMultipleMethods() {
        $route = $this->multiMethodRoute;
        $this->router->add($route);
        foreach ($route->getMethods() as $method) {
            $matches = $this->router->getMethodRoutes($method);
            $this->assertEquals($route, $matches[0]);
        }
    }
//
//    /**
//     * Routes that get added with a single method should not be accessible
//     * using other methods.
//     */
//    public function testIncorrectSingleMethodLookup() {
//        $this->router->add($this->singleMethodRoute);
//        $matches = $this->router->getMethodRoutes('post');
//
//        $this->assertEmpty($matches);
//    }

//    /**
//     * Routes that get added with a single method should not be accessible
//     * using other methods.
//     *
//     * @expectedException \Exception
//     * @expectedExceptionMessage Pattern 'home' doesn't match any defined 'patch' routes.
//     */
//    public function testIncorrectMultiMethodLookup() {
//        $this->router->add($this->multiMethodRoute);
//        $this->router->getCurrentRoute('patch', 'home');
//    }

    /**
     * Routes should be able to be looked up by their name property if it exists.
     */
    public function testValidNamedRouteLookup() {
        $this->router->add($this->singleMethodRoute);
        $named_route = $this->router->getNamedRoute('single.method.route', 'get');
        $this->assertEquals($this->singleMethodRoute, $named_route);
    }

    /**
     * Looking up a named route that does not exist should throw an exception.
     *
     * @expectedException \Exception
     * @expectedExceptionMessage No route with name 'fake.route.name' and method 'get' exists.
     */
    public function testInvalidNamedRouteLookup() {
        $this->router->add($this->singleMethodRoute);
        $named_route = $this->router->getNamedRoute('fake.route.name', 'get');
        $this->assertEquals($this->singleMethodRoute, $named_route);
    }

    /**
     * Looking up a named route that does not exist should throw an exception.
     *
     * @expectedException \Exception
     * @expectedExceptionMessage No route with name 'single.method.route' and method 'post' exists.
     */
    public function testInvalidNamedRouteMethodLookup() {
        $this->router->add($this->singleMethodRoute);
        $named_route = $this->router->getNamedRoute('single.method.route', 'post');
        $this->assertEquals($this->singleMethodRoute, $named_route);
    }

    /**
     * method getMethodRoutes should return empty array if no routes are set for method.
     */
    public function testEmptyRouteListWithReturn() {
        $this->router->add($this->singleMethodRoute);
        $this->assertEmpty($this->router->getMethodRoutes('put'));
    }
}

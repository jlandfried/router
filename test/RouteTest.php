<?php
namespace jlandfried\Router\Test;

use jlandfried\Router\Route;
use PHPUnit_Framework_TestCase;

/**
 * @group router
 */
class RouteTest extends PHPUnit_Framework_TestCase {
    /**
     * @var \jlandfried\Router\RouteInterface;
     */
    private $routeWithSingleMethod;

    /**
     * @var \jlandfried\Router\RouteInterface;
     */
    private $routeWithParameters;

    public function setUp() {
        $this->routeWithSingleMethod = new Route(['GET'], 'home', 'some--route--handler', 'route_with_single_method');
        $this->routeWithParameters = new Route('get', 'user/{uid}/edit/{profile_type}/test', 'user--edit--profile', 'user.edit.profile');
    }

    /**
     * Route methods always get converted to lowercase.
     */
    public function testGetMethodsCase() {
        $methods = $this->routeWithSingleMethod->getMethods();
        $this->assertEquals(['get'], $methods);
    }

    /**
     * Routes can store multiple methods.
     */
    public function testMultipleMethodstorage() {
        $route = new Route(['GET', 'post'], 'home', 'some--route--handler', 'route_with_multiple_methods');
        $methods = $route->getMethods();
        $this->assertEquals(['get', 'post'], $methods);
    }

    /**
     * Routes can have strings for names which can be accessed.
     */
    public function testRouteWithName() {
        $name = $this->routeWithSingleMethod->getName();
        $this->assertEquals('route_with_single_method', $name);
    }

    /**
     * Routes can not have non-null non-string values assigned to a name.
     *
     * @expectedException \Exception
     * @expectedExceptionMessage Named routes must use a string for their name.
     */
    public function testRouteWithNonStringName() {
        new Route('get', 'test', 'handler', ['array_name']);
    }
}

<?php
namespace jlandfried\Router\Test;

use jlandfried\Router\Route;
use PHPUnit_Framework_TestCase;

/**
 * @group router
 */
class RouteTest extends PHPUnit_Framework_TestCase {
    private $routeWithSingleMethod;
    private $routeWithMultipleMethods;

    public function setUp() {
        $this->routeWithSingleMethod = new Route(['GET'], 'home', 'some--route--handler');
        $this->routeWithMultipleMethods = new Route(['GET', 'post'], 'home', 'some--route--handler');
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
        $methods = $this->routeWithMultipleMethods->getMethods();
        $this->assertEquals(['get', 'post'], $methods);
    }
}

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

    /**
     * Provided url parameters should be accessible without route variable delimiters.
     */
    public function testRouteWithParametersWithoutDelimiter() {
        $params = $this->routeWithParameters->getParameters();
        $expected_params = ['uid', 'profile_type'];
        $this->assertEquals($expected_params, $params);
    }

    /**
     * Provided url parameters should be accessible with variable delimiters.
     */
    public function testRouteWithParametersWithDelimiter() {
        $params = $this->routeWithParameters->getParameters(true);
        $expected_params = ['{uid}', '{profile_type}'];
        $this->assertEquals($expected_params, $params);
    }

    /**
     * Static pattern valid match.
     */
    public function testStaticPatternValidMatch() {
        $pattern = 'static/pattern';
        $route = new Route('get', $pattern, 'fake-handler');
        $this->assertEquals(true, $route->match($pattern));
    }

    /**
     * Static pattern invalid match.
     */
    public function testStaticPatternInvalidMatch() {
        $pattern = 'static/pattern';
        $uri = 'other/pattern';
        $route = new Route('get', $pattern, 'fake-handler');
        $this->assertEquals(false, $route->match($uri));
    }

    /**
     * A provided uri can match a route with variables.
     */
    public function testRouteWithParameterMatches() {
        $uri = 'user/99/edit/master_profile/test';
        $matches = $this->routeWithParameters->match($uri);
        $this->assertEquals(true, $matches);
    }

    /**
     * A provided uri can match a route with variables.
     */
    public function testRouteWithParameterDoesNotMatch() {
        $uri = 'user/99/show/master_profile/test';
        $matches = $this->routeWithParameters->match($uri);
        $this->assertEquals(false, $matches);
    }

    /**
     * Variables at start of uri should work fine.
     */
    public function testRouteWithParameterAtBeginningMatches() {
        $pattern = '{id}/test';
        $uri = '99/test';
        $route = new Route('get', $pattern, 'fake--handler');

        $matches = $route->match($uri);
        $this->assertEquals(true, $matches);
    }

    /**
     * Variables at start of uri should fail correctly.
     */
    public function testRouteWithParameterAtBeginningDoesNotMatch() {
        $pattern = '{id}/test';
        $uri = 'test';
        $route = new Route('get', $pattern, 'fake--handler');

        $matches = $route->match($uri);
        $this->assertEquals(false, $matches);
    }

    /**
     * Variables at end of uri should work fine.
     */
    public function testRouteWithParameterAtEndMatches() {
        $pattern = 'user/{uid}/edit/{profile_type}';
        $uri = 'user/99/edit/master_profile';
        $route = new Route('get', $pattern, 'fake--handler');

        $matches = $route->match($uri);
        $this->assertEquals(true, $matches);
    }

    /**
     * Variables at end of uri should fail correctly.
     */
    public function testRouteWithParameterAtEndDoesNotMatch() {
        $pattern = 'edit/{profile_type}';
        $uri = 'blog/master_profile';
        $route = new Route('get', $pattern, 'fake--handler');

        $matches = $route->match($uri);
        $this->assertEquals(false, $matches);
    }
}

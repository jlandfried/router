<?php

namespace jlandfried\Router\Test;


use jlandfried\Router\RouteMatcher;
use jlandfried\Router\Route;
use PHPUnit_Framework_TestCase;

/**
 * @group router
 */
class RouteMatcherTest extends PHPUnit_Framework_TestCase {
    protected $routeMatcher;
    private $routeWithParameters;
    public function setUp() {
        $this->routeWithParameters = new Route('get', 'user/{uid}/edit/{profile_type}/test', 'user--edit--profile', 'user.edit.profile');
    }

    /**
     * Provided url parameters should be accessible without route variable delimiters.
     */
    public function testRouteWithParametersWithoutDelimiter() {
        $uri = 'user/99/edit/master-profile/test';
        $matcher = new RouteMatcher($this->routeWithParameters, $uri);
        $params = $matcher->getParams();
        $expected_params = ['uid', 'profile_type'];
        $this->assertEquals($expected_params, $params);
    }

    /**
     * Provided url parameters should be accessible with variable delimiters.
     */
    public function testRouteWithParametersWithDelimiter() {
        $uri = 'user/99/edit/master-profile/test';
        $matcher = new RouteMatcher($this->routeWithParameters, $uri);
        $params = $matcher->getParams(true);
        $expected_params = ['{uid}', '{profile_type}'];
        $this->assertEquals($expected_params, $params);
    }

    /**
     * Static pattern valid match.
     */
    public function testStaticPatternValidMatch() {
        $pattern = 'static/pattern';
        $route = new Route('get', $pattern, 'fake-handler');
        $matcher = new RouteMatcher($route, $pattern);
        $this->assertEquals(true, $matcher->match());
    }

    /**
     * Static pattern invalid match.
     */
    public function testStaticPatternInvalidMatch() {
        $pattern = 'static/pattern';
        $uri = 'other/pattern';
        $route = new Route('get', $pattern, 'fake-handler');
        $matcher = new RouteMatcher($route, $uri);
        $this->assertEquals(false, $matcher->match());
    }

    /**
     * A provided uri can match a route with variables.
     */
    public function testRouteWithParameterMatches() {
        $uri = 'user/99/edit/master_profile/test';
        $matcher = new RouteMatcher($this->routeWithParameters, $uri);
        $this->assertEquals(true, $matcher->match());
    }

    /**
     * A provided uri can match a route with variables.
     */
    public function testRouteWithParameterDoesNotMatch() {
        $uri = 'user/99/show/master_profile/test';
        $matcher = new RouteMatcher($this->routeWithParameters, $uri);
        $this->assertEquals(false, $matcher->match());
    }

    /**
     * Variables at start of uri should work fine.
     */
    public function testRouteWithParameterAtBeginningMatches() {
        $pattern = '{id}/test';
        $uri = '99/test';
        $route = new Route('get', $pattern, 'fake--handler');
        $matcher = new RouteMatcher($route, $uri);
        $this->assertEquals(true, $matcher->match());
    }

    /**
     * Variables at start of uri should fail correctly.
     */
    public function testRouteWithParameterAtBeginningDoesNotMatch() {
        $pattern = '{id}/test';
        $uri = 'test';
        $route = new Route('get', $pattern, 'fake--handler');
        $matcher = new RouteMatcher($route, $uri);
        $this->assertEquals(false, $matcher->match());
    }

    /**
     * Variables at end of uri should work fine.
     */
    public function testRouteWithParameterAtEndMatches() {
        $pattern = 'user/{uid}/edit/{profile_type}';
        $uri = 'user/99/edit/master_profile';
        $route = new Route('get', $pattern, 'fake--handler');
        $matcher = new RouteMatcher($route, $uri);
        $this->assertEquals(true, $matcher->match());
    }

    /**
     * Variables at end of uri should fail correctly.
     */
    public function testRouteWithParameterAtEndDoesNotMatch() {
        $pattern = 'edit/{profile_type}';
        $uri = 'blog/master_profile';
        $route = new Route('get', $pattern, 'fake--handler');
        $matcher = new RouteMatcher($route, $uri);

        $this->assertEquals(false, $matcher->match());
    }
}
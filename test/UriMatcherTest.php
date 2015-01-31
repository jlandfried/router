<?php

namespace jlandfried\Router\Test;


use jlandfried\Router\RouteMatcher;
use jlandfried\Router\RouteCollectionInterface;
use jlandfried\Router\RouteCollection;
use jlandfried\Router\Route;
use jlandfried\Router\UriMatcher;
use jlandfried\Router\UriMatcherParamBag;
use PHPUnit_Framework_TestCase;

/**
 * @group router
 */
class UriMatcherTest extends PHPUnit_Framework_TestCase {

    /**
     * @var RouteCollectionInterface
     */
    private $collection;

    /**
     * @var UriMatcher
     */
    private $matcher;
    public function setUp() {
        $this->collection = new RouteCollection();
        $this->matcher = new UriMatcher($this->collection);
    }

    /**
     * Provided url parameters should be accessible without route variable delimiters.
     */
    public function testRouteWithParametersWithoutDelimiter() {
        $route = new Route('get', '/user/{uid}/edit/{profile_type}/test', 'fake-handler');
        $params = $this->matcher->getParams($route);
        $expected_params = ['uid', 'profile_type'];
        $this->assertEquals($expected_params, $params);
    }

    /**
     * Provided url parameters should be accessible with variable delimiters.
     */
    public function testRouteWithParametersWithDelimiter() {
        $route = new Route('get', '/user/{uid}/edit/{profile_type}/test', 'fake-handler');
        $params = $this->matcher->getParams($route, true);
        $expected_params = ['{uid}', '{profile_type}'];
        $this->assertEquals($expected_params, $params);
    }

    /**
     * Static pattern valid match.
     */
    public function testStaticPatternValidMatch() {
        $this->collection->add(new Route('get', 'static/pattern', 'fake-handler'));
        $bag = new UriMatcherParamBag(['method' => 'get', 'uri' => 'static/pattern']);
        $this->assertTrue($this->matcher->match($bag));
    }

    /**
     * Static pattern invalid match.
     */
    public function testStaticPatternInvalidMatch() {
        $this->collection->add(new Route('get', 'static/pattern', 'fake-handler'));
        $bag = new UriMatcherParamBag(['method' => 'get', 'uri' => 'other/static/pattern']);
        $this->assertFalse($this->matcher->match($bag));
    }

    /**
     * A provided uri can match a route with variables.
     */
    public function testRouteWithParameterMatches() {
        $route_with_params = new Route('get', 'user/{uid}/edit/{profile_type}/test', 'user--edit--profile', 'user.edit.profile');
        $this->collection->add($route_with_params);
        $bag = new UriMatcherParamBag(['method' => 'get', 'uri' => 'user/99/edit/master_profile/test']);
        $this->assertTrue($this->matcher->match($bag));
    }

    /**
     * A provided uri can match a route with variables.
     */
    public function testRouteWithParameterDoesNotMatch() {
        $route_with_params = new Route('get', 'user/{uid}/show/{profile_type}/test', 'user--edit--profile', 'user.edit.profile');
        $this->collection->add($route_with_params);
        $bag = new UriMatcherParamBag(['method' => 'get', 'uri' => 'user/99/edit/master_profile/test']);
        $this->assertFalse($this->matcher->match($bag));
    }

    /**
     * Variables at start of uri should work fine.
     */
    public function testRouteWithParameterAtBeginningMatches() {
        $this->collection->add(new Route('get', '{id}/test', 'fake--handler'));
        $bag = new UriMatcherParamBag(['method' => 'get', 'uri' => '99/test']);
        $this->assertTrue($this->matcher->match($bag));
    }

    /**
     * Variables at start of uri should fail correctly.
     */
    public function testRouteWithParameterAtBeginningDoesNotMatch() {
        $this->collection->add(new Route('get', '{id}/test', 'fake--handler'));
        $bag = new UriMatcherParamBag(['method' => 'get', 'uri' => 'test']);
        $this->assertFalse($this->matcher->match($bag));
    }

    /**
     * Variables at end of uri should work fine.
     */
    public function testRouteWithParameterAtEndMatches() {
        $this->collection->add(new Route('get', 'test/{id}', 'fake--handler'));
        $bag = new UriMatcherParamBag(['method' => 'get', 'uri' => 'test/99']);
        $this->assertTrue($this->matcher->match($bag));
    }

    /**
     * Variables at end of uri should fail correctly.
     */
    public function testRouteWithParameterAtEndDoesNotMatch() {
        $this->collection->add(new Route('get', 'test/{id}', 'fake--handler'));
        $bag = new UriMatcherParamBag(['method' => 'get', 'uri' => 'blog/{id}']);
        $this->assertFalse($this->matcher->match($bag));
    }
}

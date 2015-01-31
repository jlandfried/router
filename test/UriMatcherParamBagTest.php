<?php

namespace jlandfried\Router\Test;

use jlandfried\Router\UriMatcherParamBag;
use PHPUnit_Framework_TestCase;

/**
 * @group router
 */
class UriMatcherParamBagTest extends PHPUnit_Framework_TestCase {

    /**
     * UriMatcherParamBag can not be created without a uri parameter.
     *
     * @expectedException \Exception
     * @expectedExceptionMessage No uri parameter included
     */
    public function testExceptionOnMissingUriParam() {
        new UriMatcherParamBag(['method' => 'get']);
    }

    /**
     * UriMatcherParamBag can not be created without a method parameter.
     *
     * @expectedException \Exception
     * @expectedExceptionMessage No method parameter included
     */
    public function testExceptionOnMissingMethodParam() {
        new UriMatcherParamBag(['uri' => 'test']);
    }

    /**
     * Get method should just return parameter values.
     */
    public function testReturnValues() {
        $bag = new UriMatcherParamBag(['method' => 'get', 'uri' => 'test']);
        $this->assertEquals('get', $bag->get('method'));
        $this->assertEquals('test', $bag->get('uri'));
    }

    /**
     * Get method should throw exception on invalid parameters.
     * @expectedException \Exception
     * @expectedExceptionMessage Invalid parameter 'foo'
     */
    public function testGetException() {
        $bag = new UriMatcherParamBag(['method' => 'get', 'uri' => 'test']);
        $bag->get('foo');
    }
}

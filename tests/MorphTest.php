<?php namespace Orchestra\Support\TestCase;

class MorphTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test MorphStub::connect() return foo_connect().
     *
     * @test
     */
    public function testStubFooConnect()
    {
        $this->assertTrue(MorphStub::connect());
    }

    /**
     * Test MorphStub::invalid() throws an Exception.
     *
     * @expectedException \RuntimeException
     */
    public function testStubFooInvalidThrowsException()
    {
        MorphStub::invalid();
    }
}

function foo_connect()
{
    return true;
}

class MorphStub extends \Orchestra\Support\Morph
{
    public static $prefix = '\Orchestra\Support\TestCase\foo_';
}

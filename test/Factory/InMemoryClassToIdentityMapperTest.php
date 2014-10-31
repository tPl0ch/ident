<?php

namespace Ident\Test\Factory;

use Ident\Factory\InMemoryClassToIdentityMapper;

/**
 * Class InMemoryClassToIdentityMapperTest
 */
class InMemoryClassToIdentityMapperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var InMemoryClassToIdentityMapper
     */
    protected $mapper;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->mapper =  new InMemoryClassToIdentityMapper();
    }

    /**
     * @test
     */
    public function shouldRegisterClassIdentityMaps()
    {
        $this->mapper->register(
            'Ident\Test\Stubs\Order',
            'Ident\Test\Stubs\OrderId'
        );

        $this->mapper->register(
            'Ident\Test\Stubs\Payment',
            'Ident\Test\Stubs\PaymentId'
        );

        $this->assertEquals('Ident\Test\Stubs\OrderId', $this->mapper->map('Ident\Test\Stubs\Order'));
        $this->assertEquals('Ident\Test\Stubs\PaymentId', $this->mapper->map('Ident\Test\Stubs\Payment'));
    }

    /**
     * @test
     */
    public function shouldMapMultipleClassesToSingleIdentity()
    {
        $this->mapper->registerMany(
            [
                'Ident\Test\Stubs\Order',
                'Ident\Test\Stubs\Payment'
            ],
            'Ident\Test\Stubs\OrderId'
        );

        $this->assertEquals('Ident\Test\Stubs\OrderId', $this->mapper->map('Ident\Test\Stubs\Order'));
        $this->assertEquals('Ident\Test\Stubs\OrderId', $this->mapper->map('Ident\Test\Stubs\Payment'));
    }

    /**
     * @test
     *
     * @expectedException \Ident\Exception\ClassAlreadyMappedException
     */
    public function shouldThrowExceptionOnMultipleMappings()
    {
        $this->mapper->registerMany(
            [
                'Ident\Test\Stubs\Order',
                'Ident\Test\Stubs\Order'
            ],
            'Ident\Test\Stubs\OrderId'
        );
    }

    /**
     * @test
     *
     * @expectedException \Ident\Exception\ClassNotFoundException
     */
    public function shouldThrowExceptionOnInvalidClass()
    {
        $this->mapper->register(
            'INVALID',
            'Ident\Test\Stubs\OrderId'
        );
    }

    /**
     * @test
     *
     * @expectedException \Ident\Exception\ClassNotFoundException
     */
    public function shouldThrowExceptionOnInvalidIdentityClass()
    {
        $this->mapper->register(
            'Ident\Test\Stubs\Order',
            'INVALID'
        );
    }

    /**
     * @test
     *
     * @expectedException \Ident\Exception\TypeNotAllowed
     */
    public function shouldThrowExceptionOnClassNotImplementingInterface()
    {
        $this->mapper->register(
            '\PHPUnit_Framework_TestCase',
            'Ident\Test\Stubs\OrderId'
        );
    }

    /**
     * @test
     *
     * @expectedException \Ident\Exception\TypeNotAllowed
     */
    public function shouldThrowExceptionOnIdentityClassNotImplementingInterface()
    {
        $this->mapper->register(
            'Ident\Test\Stubs\OrderId',
            '\PHPUnit_Framework_TestCase'
        );
    }
}

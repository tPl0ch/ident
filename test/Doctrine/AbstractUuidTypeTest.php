<?php

namespace Ident\Test\Doctrine;

use Doctrine\DBAL\Types\Type;

/**
 * Class AbstractUuidTypeTest
 */
abstract class AbstractUuidTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var mixed
     */
    private $signature;

    /**
     * @var \Ident\IdentifiesObjects
     */
    private $identifier;

    private $platform;

    /**
     * @var \Doctrine\DBAL\Types\Type
     */
    private $type;

    abstract public function instanceProvider();

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        list($typeClass, $idClass, $this->signature) = $this->instanceProvider();
        $this->identifier = $idClass::fromSignature($this->signature);

        $typeName = constant($typeClass . '::NAME');

        if (!Type::hasType($typeName)) {
            Type::addType($typeName, $typeClass);
        }

        $this->type = Type::getType($typeName);

        $this->platform = $this->getMockBuilder('Doctrine\DBAL\Platforms\AbstractPlatform')
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
    }

    /**
     * @test
     */
    public function shouldGenerateFromIdentifier()
    {
        $signature = $this->type->convertToDatabaseValue($this->identifier, $this->platform);
        $this->assertEquals($this->signature, $signature);
    }

    /**
     * @test
     */
    public function shouldGenerateFromSignature()
    {
        $identifier = $this->type->convertToPHPValue($this->signature, $this->platform);
        $this->assertEquals($this->identifier, $identifier);
    }
}

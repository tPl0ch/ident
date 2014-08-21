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
    protected $signature;

    /**
     * @var \Ident\IdentifiesObjects
     */
    protected $identifier;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $platform;

    /**
     * @var \Doctrine\DBAL\Types\Type
     */
    protected $type;

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
            ->setMethods(['getBinaryTypeDeclarationSQL', 'getVarcharTypeDeclarationSQL'])
            ->getMockForAbstractClass();
    }

    /**
     * @test
     */
    public function shouldGenerateFromIdentifier()
    {
        $signature = $this->type->convertToDatabaseValue($this->identifier, $this->platform);
        $this->assertEquals($this->signature, $signature);

        $this->assertNull($this->type->convertToDatabaseValue(null, $this->platform));
    }

    /**
     * @test
     */
    public function shouldGenerateFromSignature()
    {
        $identifier = $this->type->convertToPHPValue($this->signature, $this->platform);
        $this->assertEquals($this->identifier, $identifier);

        $this->assertNull($this->type->convertToPHPValue(null, $this->platform));
    }

    /**
     * @test
     *
     * @expectedException \Doctrine\DBAL\Types\ConversionException
     */
    public function shouldThrowExceptionOnInvalidPhpValue()
    {
        $this->type->convertToPHPValue('INVALID', $this->platform);
    }

    /**
     * @test
     *
     * @expectedException \Doctrine\DBAL\Types\ConversionException
     */
    public function shouldThrowExceptionOnInvalidDbValue()
    {
        $this->type->convertToDatabaseValue('INVALID', $this->platform);
    }
}

<?php

namespace Ident\Test\Doctrine;

/**
 * Class UuidTypeTest
 */
class UuidTypeTest extends AbstractUuidTypeTest
{
    /**
     * @return string
     */
    public function instanceProvider()
    {
        return [
            'Ident\Doctrine\Type\UuidType',
            'Ident\Identifiers\UuidIdentifier',
            '62e1a130-7ac0-4486-8756-ccd2a108eb4a'
        ];
    }

    /**
     * @test
     */
    public function shouldCallVarcharFieldSql()
    {
        $this
            ->platform
            ->expects($this->once())
            ->method('getVarcharTypeDeclarationSQL')
            ->with(
                [
                    'length' => 36,
                    'fixed'  => true
                ]
            )
            ->will($this->returnValue('StringSQL'));

        $this->assertEquals(
            'StringSQL',
            $this->type->getSQLDeclaration(['length' => 16, 'fixed' => false], $this->platform)
        );
    }
}

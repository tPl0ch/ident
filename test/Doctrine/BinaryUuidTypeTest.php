<?php

namespace Ident\Test\Doctrine;

/**
 * Class BinaryUuidTypeTest
 */
class BinaryUuidTypeTest extends AbstractUuidTypeTest
{
    /**
     * @return string
     */
    public function instanceProvider()
    {
        return [
            'Ident\Doctrine\Type\BinaryUuidType',
            'Ident\Identifiers\BinaryUuidIdentifier',
            hex2bin('0ec045b9e40748c5b43b956ddb087de1')
        ];
    }

    /**
     * @test
     */
    public function shouldCallBinaryFieldSql()
    {
        $this
            ->platform
            ->expects($this->once())
            ->method('getBinaryTypeDeclarationSQL')
            ->with(
                [
                    'length' => 16,
                    'fixed'  => true
                ]
            )
            ->will($this->returnValue('BinarySQL'));

        $this->assertEquals(
            'BinarySQL',
            $this->type->getSQLDeclaration(['length' => 36, 'fixed' => false], $this->platform)
        );
    }
}

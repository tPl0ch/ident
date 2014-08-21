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
            'Ident\Doctrine\BinaryUuidType',
            'Ident\Identifiers\BinaryUuidIdentifier',
            hex2bin('0ec045b9e40748c5b43b956ddb087de1')
        ];
    }
}

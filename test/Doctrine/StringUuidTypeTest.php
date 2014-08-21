<?php

namespace Ident\Test\Doctrine;

/**
 * Class StringUuidTypeTest
 */
class StringUuidTypeTest extends AbstractUuidTypeTest
{
    /**
     * @return string
     */
    public function instanceProvider()
    {
        return [
            'Ident\Doctrine\StringUuidType',
            'Ident\Identifiers\StringUuidIdentifier',
            '62e1a130-7ac0-4486-8756-ccd2a108eb4a'
        ];
    }
}

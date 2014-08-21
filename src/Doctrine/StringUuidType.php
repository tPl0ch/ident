<?php

namespace Ident\Doctrine;

/**
 * Class StringUuidType
 */
class StringUuidType extends AbstractUuidType
{
    /** @var string */
    const NAME = 'uuid_string';

    /**
     * The class name of the UuidType
     *
     * @return string
     */
    protected function getClass()
    {
        return 'Ident\Identifiers\StringUuidIdentifier';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return self::NAME;
    }
}

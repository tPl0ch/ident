<?php

namespace Ident\Doctrine;

/**
 * Class BinaryUuidType
 */
class BinaryUuidType extends AbstractUuidType
{
    /** @var string */
    const NAME = 'uuid_binary';

    /**
     * The class name of the UuidType
     *
     * @return string
     */
    protected function getClass()
    {
        return 'Ident\Identifiers\BinaryUuidIdentifier';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return self::NAME;
    }
}

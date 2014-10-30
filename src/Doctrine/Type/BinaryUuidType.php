<?php

namespace Ident\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * Class BinaryUuidType
 */
class BinaryUuidType extends AbstractUuidType
{
    /** @var string */
    const NAME = 'uuid_binary';

    /** @var string */
    const DEFAULT_CLASS = 'Ident\Identifiers\BinaryUuidIdentifier';

    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        $fieldDeclaration['fixed']  = true;
        $fieldDeclaration['length'] = 16;

        return $platform->getBinaryTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * The class name of the UuidType
     *
     * @return string
     */
    protected function getClass()
    {
        return static::DEFAULT_CLASS;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return self::NAME;
    }
}

<?php

namespace Ident\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * Class UuidType
 */
class UuidType extends AbstractUuidType
{
    /** @var string */
    const NAME = 'uuid';

    /** @var string */
    const DEFAULT_CLASS = 'Ident\Identifiers\UuidIdentifier';

    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        $fieldDeclaration['fixed']  = true;
        $fieldDeclaration['length'] = 36;

        return $platform->getVarcharTypeDeclarationSQL($fieldDeclaration);
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

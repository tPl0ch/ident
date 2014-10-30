<?php

namespace Ident\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * Class StringUuidType
 */
class StringUuidType extends AbstractUuidType
{
    /** @var string */
    const NAME = 'uuid_string';

    /** @var string */
    const DEFAULT_CLASS = 'Ident\Identifiers\StringUuidIdentifier';

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

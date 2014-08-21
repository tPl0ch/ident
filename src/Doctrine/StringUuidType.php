<?php

namespace Ident\Doctrine;
use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * Class StringUuidType
 */
class StringUuidType extends AbstractUuidType
{
    /** @var string */
    const NAME = 'uuid_string';

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

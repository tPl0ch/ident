<?php

namespace Ident\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

use Ident\Identifiers\StringUuidIdentifier;
use Ident\Exception\InvalidSignature;

/**
 * Class StringUuidType
 */
class StringUuidType extends Type
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

        return $platform->getGuidTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (empty($value)) {
            return null;
        }

        if (!$value instanceof StringUuidIdentifier) {
            throw ConversionException::conversionFailed($value, self::NAME);
        }

        return $value->signature();
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (empty($value)) {
            return null;
        }

        try {
            $stringUuidId = StringUuidIdentifier::fromSignature($value);
        } catch (InvalidSignature $e) {
            throw ConversionException::conversionFailed($value, self::NAME);
        }

        return $stringUuidId;
    }


    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return self::NAME;
    }
}

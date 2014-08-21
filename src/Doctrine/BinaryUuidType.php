<?php

namespace Ident\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

use Ident\Exception\InvalidSignature;
use Ident\Identifiers\BinaryUuidIdentifier;

/**
 * Class BinaryUuidType
 */
class BinaryUuidType extends Type
{
    /** @var string */
    const NAME = 'uuid_binary';

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
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (empty($value)) {
            return null;
        }

        if (!$value instanceof BinaryUuidIdentifier) {
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
            $binaryUuidId = BinaryUuidIdentifier::fromSignature($value);
        } catch (InvalidSignature $e) {
            throw ConversionException::conversionFailed($value, self::NAME);
        }

        return $binaryUuidId;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return self::NAME;
    }
}

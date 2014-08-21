<?php

namespace Ident\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Ident\Exception\InvalidSignature;

/**
 * Class AbstractUuidType
 */
abstract class AbstractUuidType extends Type
{
    /**
     * The class name of the UuidIdentifier type
     *
     * @return string
     */
    abstract protected function getClass();

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (empty($value)) {
            return null;
        }

        $class = $this->getClass();

        if (!$value instanceof $class) {
            throw ConversionException::conversionFailed($value, $this->getName());
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

        $class = $this->getClass();

        try {
            $uuid = $class::fromSignature($value);
        } catch (InvalidSignature $e) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }

        return $uuid;
    }
}

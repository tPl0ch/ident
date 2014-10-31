<?php

namespace Ident\Exception;

use Ident\HasIdentity;
use Ident\HasScalarId;
use Ident\IdentifiesObjects;

/**
 * Class IdentExceptions
 */
final class IdentExceptions
{
    /**
     * @param string|array $signature
     *
     * @return InvalidSignature
     */
    public static function invalidSignature($signature)
    {
        if (is_array($signature)) {
            $signature = implode(',', $signature);
        }

        return new InvalidSignature(
            sprintf("The signature '%s' is invalid.", (string) $signature)
        );
    }

    /**
     * @param HasIdentity $identity
     *
     * @return IdentityAlreadyRegistered
     */
    public static function identityAlreadyRegistered(HasIdentity $identity)
    {
        $signature = $identity->getIdentifier()->signature();

        if ($identity instanceof HasScalarId) {
            $signature = $identity->getIdValue();
        }

        return new IdentityAlreadyRegistered(
            sprintf("Identity with signature '%s' is already registered", $signature)
        );
    }

    /**
     * @param IdentifiesObjects $id
     *
     * @return IdentityNotFound
     */
    public static function identityNotFound(IdentifiesObjects $id)
    {
        return new IdentityNotFound(
            sprintf("Identity with signature '%s' could not be found.", $id->signature())
        );
    }

    /**
     * @param string|object $type
     *
     * @return TypeNotAllowed
     */
    public static function typeNotAllowed($type)
    {
        if (is_object($type)) {
            $type = get_class($type);
        }

        return new TypeNotAllowed(
            sprintf("The type '%s' is not allowed.", $type)
        );
    }

    /**
     * @param string $class
     *
     * @return ClassNotMappableException
     */
    public static function classNotMappable($class)
    {
        return new ClassNotMappableException(
            sprintf("Class '%s' cannot be mapped.", $class)
        );
    }

    /**
     * @param string $class
     *
     * @return ClassNotFoundException
     */
    public static function classNotFound($class)
    {
        return new ClassNotFoundException(
            sprintf("Class '%s' could not be found or autoloaded.", $class)
        );
    }

    /**
     * @param string $class
     *
     * @return ClassAlreadyMappedException
     */
    public static function classAlreadyMapped($class)
    {
        return new ClassAlreadyMappedException(
            sprintf("Class '%s' is already mapped", $class)
        );
    }
}

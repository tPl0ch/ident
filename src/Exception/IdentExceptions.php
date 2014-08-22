<?php

namespace Ident\Exception;

/**
 * Class IdentExceptions
 */
final class IdentExceptions
{
    /**
     * @return InvalidSignature
     */
    public static function invalidSignature()
    {
        return new InvalidSignature();
    }

    /**
     * @return IdentityAlreadyRegistered
     */
    public static function identityAlreadyRegistered()
    {
        return new IdentityAlreadyRegistered();
    }

    /**
     * @return IdentityNotFound
     */
    public static function identityNotFound()
    {
        return new IdentityNotFound();
    }

    /**
     * @return TypeNotAllowed
     */
    public static function typeNotAllowed()
    {
        return new TypeNotAllowed();
    }
}

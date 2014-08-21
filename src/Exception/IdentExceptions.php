<?php

namespace Ident\Exception;

/**
 * Class IdentExceptions
 */
final class IdentExceptions
{
    /**
     * @throws InvalidSignature
     */
    public static function invalidSignature()
    {
        return new InvalidSignature();
    }
}

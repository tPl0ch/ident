<?php
/**
 * @author Thomas Ploch <thomas.ploch@meinfernbus.de>
 */
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

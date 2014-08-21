<?php
/**
 * @author Thomas Ploch <thomas.ploch@meinfernbus.de>
 */
namespace Ident;

/**
 * Interface IdentifiesObjects
 */
interface IdentifiesObjects
{
    /**
     * @param mixed $signature
     *
     * @return IdentifiesObjects
     */
    public static function fromSignature($signature);

    /**
     * This method returns a unique identity representation
     *
     * @return mixed
     */
    public function signature();

    /**
     * @param IdentifiesObjects $id
     *
     * @return bool
     */
    public function equals(IdentifiesObjects $id);
}

<?php

namespace Ident;

/**
 * Interface IdentifiesObjects
 */
interface IdentifiesObjects extends IdentityCanBeCompared
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
}

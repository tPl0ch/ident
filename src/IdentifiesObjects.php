<?php

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
     * @return string
     */
    public function __toString();
}

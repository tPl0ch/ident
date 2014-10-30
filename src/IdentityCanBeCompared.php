<?php

namespace Ident;

/**
 * Interface CanBeCompared
 */
interface IdentityCanBeCompared
{
    /**
     * @param IdentifiesObjects $id
     *
     * @return bool
     */
    public function equals(IdentifiesObjects $id);
}

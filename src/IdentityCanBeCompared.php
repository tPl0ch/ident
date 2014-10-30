<?php

namespace Ident;

/**
 * Interface IdentityCanBeCompared
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

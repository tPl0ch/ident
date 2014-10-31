<?php

namespace Ident;

/**
 * Interface CreatesIdentities
 */
interface CreatesIdentities
{
    /**
     * @return \Ident\IdentifiesObjects
     */
    public function identify();
}

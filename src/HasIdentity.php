<?php

namespace Ident;

/**
 * Interface HasIdentity
 */
interface HasIdentity
{
    /**
     * @return IdentifiesObjects
     */
    public function getIdentifier();
}

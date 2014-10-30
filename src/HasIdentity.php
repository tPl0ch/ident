<?php

namespace Ident;

/**
 * Interface HasIdentity
 */
interface HasIdentity extends ObjectCanBeCompared
{
    /**
     * @return IdentifiesObjects
     */
    public function getIdentifier();
}

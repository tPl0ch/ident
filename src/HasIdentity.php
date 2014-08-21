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

    /**
     * @param HasIdentity $identity
     *
     * @return bool
     */
    public function equals(HasIdentity $identity);
}

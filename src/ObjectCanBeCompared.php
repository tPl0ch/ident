<?php

namespace Ident;

/**
 * Interface ObjectCanBeCompared
 */
interface ObjectCanBeCompared
{
    /**
     * @param HasIdentity $object
     *
     * @return bool
     */
    public function equals(HasIdentity $object);
}

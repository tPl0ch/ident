<?php

namespace Ident;

/**
 * Interface RegistersIdentities
 */
interface RegistersIdentities
{
    /**
     * @param HasIdentity $identity
     *
     * @return void
     */
    public function add(HasIdentity $identity);

    /**
     * @param HasIdentity $identity
     *
     * @return bool
     */
    public function contains(HasIdentity $identity);

    /**
     * @param HasIdentity $identity
     *
     * @return void
     */
    public function remove(HasIdentity $identity);

    /**
     * @param IdentifiesObjects $id
     *
     * @return HasIdentity $identity
     */
    public function get(IdentifiesObjects $id);

    /**
     * @return \Iterator
     */
    public function all();

    /**
     * @return void
     */
    public function clear();

    /**
     * @param IdentifiesObjects $id
     *
     * @return bool
     */
    public function has(IdentifiesObjects $id);

    /**
     * @param IdentifiesObjects $id
     *
     * @return void
     */
    public function del(IdentifiesObjects $id);
}

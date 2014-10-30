<?php

namespace Ident\Registry;

use Ident\Exception\IdentExceptions;
use Ident\HasIdentity;
use Ident\IdentifiesObjects;
use Ident\RegistersIdentities;
use iter;

/**
 * Class InMemoryRegistry
 */
class InMemoryRegistry implements RegistersIdentities
{
    /**
     * @var \SplObjectStorage
     */
    protected $map;

    /**
     * @var bool
     */
    protected $asArray = false;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->map = new \SplObjectStorage();
    }

    /**
     * @param HasIdentity $identity
     *
     * @throws \Ident\Exception\IdentityAlreadyRegistered
     */
    public function add(HasIdentity $identity)
    {
        iter\any(
            function ($item) use ($identity) {
                /** @var IdentifiesObjects $item */
                if ($item->equals($identity->getIdentifier())) {
                    throw IdentExceptions::identityAlreadyRegistered();
                }

                return true;
            },
            $this->map
        );

        $this->map->attach($identity->getIdentifier(), $identity);
    }

    /**
     * @param HasIdentity $identity
     *
     * @return bool
     */
    public function contains(HasIdentity $identity)
    {
        return $this->map->contains($identity->getIdentifier());
    }

    /**
     * @param HasIdentity $identity
     *
     * @return void
     */
    public function remove(HasIdentity $identity)
    {
        $this->map->detach($identity->getIdentifier());
    }

    /**
     * @param IdentifiesObjects $id
     *
     * @return HasIdentity
     * @throws \Ident\Exception\IdentityNotFound
     */
    public function get(IdentifiesObjects $id)
    {
        if (!$this->map->contains($id)) {
            throw IdentExceptions::identityNotFound();
        }

        return $this->map[$id];
    }

    /**
     * @return \Iterator
     */
    public function all()
    {
        $iterator = iter\rewindable\map(
            function ($item) {
                return $this->map[$item];
            },
            $this->map
        );

        return $this->iteratorToArray($iterator);
    }

    /**
     * @param callable $callable
     *
     * @return void
     */
    public function map(Callable $callable)
    {
        iter\apply(
            function ($identifier) use ($callable) {
                $callable($this->map[$identifier]);
            },
            $this->map
        );
    }

    /**
     * @return void
     */
    public function clear()
    {
        $this->map = new \SplObjectStorage();
    }

    /**
     * @param IdentifiesObjects $id
     *
     * @return bool
     */
    public function has(IdentifiesObjects $id)
    {
        return $this->map->contains($id);
    }

    /**
     * @param IdentifiesObjects $id
     */
    public function del(IdentifiesObjects $id)
    {
        $this->map->detach($id);
    }

    /**
     * @return $this
     */
    public function asArray()
    {
        $this->asArray = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function asIterator()
    {
        $this->asArray = false;

        return $this;
    }

    /**
     * @param \Iterator $iterator
     *
     * @return array|\Iterator
     */
    protected function iteratorToArray(\Iterator $iterator)
    {
        if (!$this->asArray) {
            return $iterator;
        }

        $this->asArray = false;

        return iter\toArray($iterator);
    }
}

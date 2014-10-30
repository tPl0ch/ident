<?php
/**
 * @author Thomas Ploch <thomas.ploch@meinfernbus.de>
 */
namespace Ident\Registry\Decorator;

use Ident\HasIdentity;
use Ident\IdentifiesObjects;
use Ident\RegistersIdentities;

/**
 * Class GuardedRegistry
 */
class GuardedRegistry implements RegistersIdentities
{
    /**
     * @var callable
     */
    protected $guard;

    /**
     * @var RegistersIdentities
     */
    protected $registry;

    /**
     * @param RegistersIdentities $registry Registry
     * @param callable            $guard    Guard callable
     */
    public function __construct(RegistersIdentities $registry, callable $guard)
    {
        $this->registry = $registry;
        $this->guard    = $guard;
    }

    /**
     * @param HasIdentity $identity
     *
     * @return void
     */
    public function add(HasIdentity $identity)
    {
        call_user_func($this->guard, $identity);

        $this->registry->add($identity);
    }

    /**
     * @param HasIdentity $identity
     *
     * @return bool
     */
    public function contains(HasIdentity $identity)
    {
        return $this->registry->contains($identity);
    }

    /**
     * @param HasIdentity $identity
     *
     * @return void
     */
    public function remove(HasIdentity $identity)
    {
        $this->registry->remove($identity);
    }

    /**
     * @param IdentifiesObjects $id
     *
     * @return HasIdentity $identity
     */
    public function get(IdentifiesObjects $id)
    {
        return $this->registry->get($id);
    }

    /**
     * @return \Iterator
     */
    public function all()
    {
        return $this->registry->all();
    }

    /**
     * @return void
     */
    public function clear()
    {
        $this->registry->clear();
    }

    /**
     * @param callable $callable
     *
     * @return void
     */
    public function map(Callable $callable)
    {
        $this->registry->map($callable);
    }

    /**
     * @param IdentifiesObjects $id
     *
     * @return bool
     */
    public function has(IdentifiesObjects $id)
    {
        return $this->registry->has($id);
    }

    /**
     * @param IdentifiesObjects $id
     *
     * @return void
     */
    public function del(IdentifiesObjects $id)
    {
        $this->registry->del($id);
    }
}

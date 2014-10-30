<?php
/**
 * @author Thomas Ploch <thomas.ploch@meinfernbus.de>
 */
namespace Ident\Traits;

use Ident\IdentifiesObjects;

/**
 * Class Equalizer
 */
trait Identifier
{
    /**
     * @var mixed
     */
    protected $signature;

    /**
     * @return mixed
     */
    public function signature()
    {
        return $this->signature;
    }

    /**
     * @param IdentifiesObjects $id
     *
     * @return bool
     */
    public function equals(IdentifiesObjects $id)
    {
        return $this->signature() === $id->signature();
    }
}

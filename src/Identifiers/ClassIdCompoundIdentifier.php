<?php

namespace Ident\Identifiers;

use Ident\Exception\IdentExceptions;
use Ident\HasDefiningClass;
use Ident\HasScalarId;
use Ident\IdentifiesObjects;
use Ident\Traits\Identifier;

/**
 * Class ClassIdCompoundIdentifier
 */
class ClassIdCompoundIdentifier
    implements IdentifiesObjects, HasScalarId, HasDefiningClass
{
    /** @var string */
    const SEPARATOR = ':';

    use Identifier;

    /**
     * @var string
     */
    protected $className;

    /**
     * @var string
     */
    protected $identifier;

    /**
     * @param mixed $signature
     *
     * @return IdentifiesObjects
     *
     * @throws \Ident\Exception\InvalidSignature
     */
    public static function fromSignature($signature)
    {
        $signatureArray = explode(static::SEPARATOR, $signature);

        if (count($signatureArray) !== 2) {
            throw IdentExceptions::invalidSignature($signature);
        }

        return new static($signatureArray[0], $signatureArray[1]);
    }

    /**
     * @param string $className
     * @param string $identifier
     */
    public function __construct($className, $identifier)
    {
        $this->className = $className;
        $this->identifier = $identifier;

        $this->signature = sprintf('%s%s%s', $className, static::SEPARATOR, $identifier);
    }

    /**
     * @return string
     */
    public function getIdValue()
    {
        return $this->identifier;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->className;
    }
}

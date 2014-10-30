<?php

namespace Ident\Doctrine\Mapping\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * Class IdType
 *
 * This annotation provides a way to change the identifier to the specified class
 *
 * @Annotation
 * @Target("PROPERTY")
 */
final class IdType extends Annotation
{
    const DEFAULT_CLASS = '/Ident/Identifiers/ClassIdCompoundIdentifier';

    /**
     * @var string
     */
    public $idClass = self::DEFAULT_CLASS;

    /**
     * @var string|array
     */
    public $field;

    /**
     * @var mixed
     */
    public $value;
}

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
    /**
     * @var string
     */
    public $idClass;

    /**
     * @var string|array
     */
    public $field;

    /**
     * @var mixed
     */
    public $value;
}

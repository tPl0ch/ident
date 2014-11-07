<?php

namespace Ident\Metadata;

use Metadata\PropertyMetadata as BasePropertyMetadata;

/**
 * Class PropertyMetadata
 */
class PropertyMetadata extends BasePropertyMetadata
{
    /**
     * @var string
     */
    public $type;

    /**
     * @var string|array|null
     */
    public $factory;
}

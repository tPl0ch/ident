<?php

namespace Ident\Doctrine\Mapping\Driver;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\Common\Persistence\Mapping\Driver\AnnotationDriver as AbstractAnnotationDriver;

class AnnotationDriver extends AbstractAnnotationDriver
{
    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function loadMetadataForClass($className, ClassMetadata $metadata)
    {
    }

    /**
     * {@inheritdoc}
     *
     * @return array The names of all mapped classes known to this driver.
     */
    public function getAllClassNames()
    {
        // TODO: Implement getAllClassNames() method.
    }

    /**
     * {@inheritdoc}
     *
     * @return boolean
     */
    public function isTransient($className)
    {
        // TODO: Implement isTransient() method.
    }
} 

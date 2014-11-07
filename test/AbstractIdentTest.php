<?php

namespace Ident\Test;

use Ident\Metadata\Driver\AnnotationDriver;
use Ident\Metadata\Processor\IdentityMetadataProcessor;
use Metadata\MetadataFactory;

/**
 * Class AbstractIdentTest
 */
abstract class AbstractIdentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IdentityMetadataProcessor
     */
    protected $processor;

    /**
     * @return \Ident\ServiceLocatorInterface
     */
    protected function getContainer()
    {
        return $_ENV['container'];
    }

    /**
     * @return IdentityMetadataProcessor
     */
    protected function getProcessor()
    {
        if ($this->processor) {
            return $this->processor;
        }

        $container = $this->getContainer();

        $driver = new AnnotationDriver(
            $container->get('annotation.reader')
        );

        $this->processor = new IdentityMetadataProcessor(
            new MetadataFactory($driver),
            $this->getContainer(),
            $this->getContainer()->get('mapper')
        );

        return $this->processor;
    }
}

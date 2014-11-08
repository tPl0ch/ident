<?php

namespace Ident\Doctrine\Subscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Ident\HasIdentity;
use Ident\Metadata\Processor\IdentityMetadataProcessor;

/**
 * Class IdentitySubscriber
 */
class IdentitySubscriber implements EventSubscriber
{
    /**
     * @var IdentityMetadataProcessor
     */
    protected $processor;

    /**
     * @param IdentityMetadataProcessor $processor
     */
    public function __construct(IdentityMetadataProcessor $processor)
    {
        $this->processor = $processor;
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
            Events::postLoad
        ];
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args) {
        $this->process($args);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postLoad(LifecycleEventArgs $args) {
        $this->process($args, true);
    }

    /**
     * @param LifecycleEventArgs $args
     * @param boolean            $convertIdentifiers
     */
    public function process(LifecycleEventArgs $args, $convertIdentifiers = false)
    {
        $object = $args->getObject();

        if (!$object instanceof HasIdentity) {
            return;
        }

        $this->processor->processIdentities($object, $convertIdentifiers);
    }
}

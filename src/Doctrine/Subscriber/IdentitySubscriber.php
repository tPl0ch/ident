<?php

namespace Ident\Doctrine\Subscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Ident\CreatesIdentities;
use Ident\HasIdentity;

/**
 * Class IdentitySubscriber
 */
class IdentitySubscriber implements EventSubscriber
{
    /**
     * @var CreatesIdentities
     */
    protected $processor;

    /**
     * @param CreatesIdentities $processor
     */
    public function __construct(CreatesIdentities $processor)
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

        $this->processor->identify($object, $convertIdentifiers);
    }
}

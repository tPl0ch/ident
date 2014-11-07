<?php

namespace Ident\ServiceLocator;

use Ident\ServiceLocatorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class SymfonyServiceLocator
 */
class SymfonyServiceLocator implements ServiceLocatorInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $serviceName
     * @param array  $params
     *
     * @return object
     */
    public function get($serviceName, array $params = [])
    {
        return call_user_func_array([$this->container, 'get'], $params);
    }
}

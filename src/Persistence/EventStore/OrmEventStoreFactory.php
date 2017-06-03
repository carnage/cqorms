<?php

namespace Carnage\Cqorms\Persistence\EventStore;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class OrmEventStoreFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, OrmEventStore::class);
    }

    public function __invoke(ContainerInterface $container, $name, array $options = [])
    {
        return new OrmEventStore($container->get('doctrine.entitymanager.orm_default'));
    }
}
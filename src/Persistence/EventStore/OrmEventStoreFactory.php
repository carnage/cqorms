<?php

namespace Carnage\Cqorms\Persistence\EventStore;

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
        return new OrmEventStore($serviceLocator->get('doctrine.entitymanager.orm_default'));
    }
}
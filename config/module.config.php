<?php

use Carnage\Cqrs\Persistence;
use Carnage\Cqorms\Persistence as OrmPersistence;

return [
    'service_manager' => [
        'factories' => [
            OrmPersistence\EventStore\OrmEventStore::class => OrmPersistence\EventStore\OrmEventStoreFactory::class
        ],
        'aliases' => [
            Persistence\EventStore\EventStoreInterface::class =>
                OrmPersistence\EventStore\OrmEventStore::class
        ]
    ],
    'doctrine' => array(
        'driver' => array(
            'cqorms' => array(
                'class' =>'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Entity')
            ),
            'cqrs' => array(
                'class' =>\Doctrine\ORM\Mapping\Driver\XmlDriver::class,
                'cache' => 'array',
                'paths' => array(__DIR__ . '/mapping')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Carnage\Cqorms\Entity' => 'cqorms',
                    'Carnage\Cqrs\Event' => 'cqrs'
                )
            )
        ),
    ),
];

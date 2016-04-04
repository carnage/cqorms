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
    'doctrine' => [
        'configuration' => [
            'orm_default' => [
                'types' => [
                    'json_object' => \Carnage\Cqorms\Dbal\JsonObject::class
                ],
                'doctrine_type_mappings' => [
                    'json_object' => 'json_object'
                ],
            ]
        ],
        'driver' => [
            'cqorms' => [
                'class' =>'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
            ],
            'cqrs' => [
                'class' =>\Doctrine\ORM\Mapping\Driver\XmlDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/mapping']
            ],
            'orm_default' => [
                'drivers' => [
                    'Carnage\Cqorms\Entity' => 'cqorms',
                    'Carnage\Cqrs\Event' => 'cqrs'
                ]
            ]
        ],
    ],
];

<?php

namespace Carnage\Cqorms\Persistence\EventStore;

use Carnage\Cqorms\Entity\DomainMessage;
use Carnage\Cqorms\Entity\Event;
use Carnage\Cqrs\Persistence\EventStore\EventStoreInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class OrmEventStore
 * @package Carnage\Cqorms\Persistence\EventStore
 * @TODO handle versioning.
 */
final class OrmEventStore implements EventStoreInterface
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;
    private $eventEntity = DomainMessage::class;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function load($aggregateType, $id)
    {
        $className = $this->eventEntity;
        $repository = $this->entityManager->getRepository($className);
        $eventsCollection = $repository->findBy([$className::AGGREGATE_ID => $id, $className::AGGREGATE_TYPE => $aggregateType]);

        $events = [];
        foreach ($eventsCollection as $event) {
            $events[] = $event->getPayload();
        }

        if (empty($events)) {
            throw new \Exception('Not found');
        }

        return $events;
    }

    public function save($aggregateType, $id, $events)
    {
        $this->entityManager->beginTransaction();

        foreach ($events as $event) {
            $entity = new Event($id, $aggregateType, $event);
            $this->entityManager->persist($entity);
        }
        $this->entityManager->flush();
        $this->entityManager->commit();
    }

}
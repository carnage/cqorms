<?php

namespace Carnage\Cqorms\Persistence\EventStore;

use Carnage\Cqorms\Entity\DomainMessage;
use Carnage\Cqrs\Persistence\EventStore\EventStoreInterface;
use Carnage\Cqrs\Persistence\EventStore\LoadEventsInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class OrmEventStore
 * @package Carnage\Cqorms\Persistence\EventStore
 * @TODO handle versioning.
 */
final class OrmEventStore implements EventStoreInterface, LoadEventsInterface
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

        $events = $this->processEvents($eventsCollection);

        if (empty($events)) {
            throw new \Exception('Not found');
        }

        return $events;
    }

    public function save($aggregateType, $id, $events)
    {
        $this->entityManager->beginTransaction();
        $className = $this->eventEntity;

        foreach ($events as $event) {
            $entity = new $className($event);
            $this->entityManager->persist($entity);
        }
        $this->entityManager->flush();
        $this->entityManager->commit();
    }

    public function loadAllEvents(): array
    {
        $className = $this->eventEntity;
        $repository = $this->entityManager->getRepository($className);
        $eventsCollection = $repository->findAll();

        return $this->processEvents($eventsCollection);
    }

    public function loadEventsByTypes(string ...$eventTypes): array
    {
        $className = $this->eventEntity;
        $queryBuilder = $this->entityManager->getRepository($className)->createQueryBuilder('e');
        $eventsCollection = $queryBuilder->where(sprintf('e.%s in (:eventTypes)', $className::EVENT_CLASS))
            ->setParameter('eventTypes', $eventTypes)
            ->getQuery()
            ->getResult();

        return $this->processEvents($eventsCollection);
    }

    /**
     * @param $eventsCollection
     * @return array
     */
    private function processEvents($eventsCollection)
    {
        $events = [];
        foreach ($eventsCollection as $event) {
            $events[] = $event->getPayload();
        }

        return $events;
    }


}
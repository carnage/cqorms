<?php

namespace Carnage\Cqorms\Persistence\ReadModel;

use Carnage\Cqrs\Persistence\ReadModel\RepositoryInterface;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineRepository implements RepositoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    private $entity;

    public function __construct($entity, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->entity = $entity;
    }

    public function add($element)
    {
        $this->entityManager->persist($element);
    }

    public function remove($element)
    {
        $this->entityManager->remove($element);
    }

    public function get($key)
    {
        $this->entityManager->getRepository($this->entity)->find($key);
    }

    public function matching(Criteria $criteria)
    {
        /** @var \Doctrine\ORM\EntityRepository $repository*/
        $repository = $this->entityManager->getRepository($this->entity);
        return $repository->matching($criteria);
    }

    public function commit()
    {
        $this->entityManager->flush();
    }
}

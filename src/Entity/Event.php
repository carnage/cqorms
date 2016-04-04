<?php

namespace Carnage\Cqorms\Entity;

use Carnage\Cqrs\Event\DomainMessage as DomainMessageWrapper;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Event
 * @package Carnage\Cqorms\Entity
 * @ORM\Entity()
 */
class Event
{
    const AGGREGATE_ID = 'aggregateId';
    const AGGREGATE_TYPE = 'aggregateType';
    /**
     * @var integer
     * @ORM\Id @ORM\GeneratedValue @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    protected $aggregateId;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    protected $aggregateType;

    /**
     * @ORM\Column(type="object")
     */
    protected $payload;

    public function __construct(DomainMessageWrapper $payload)
    {
        $this->aggregateId = $payload->getAggregateId();
        $this->payload = $payload;
        $this->aggregateType = $payload->getAggregateClass();
    }

    /**
     * @return mixed
     */
    public function getPayload()
    {
        return $this->payload;
    }
}
<?php

namespace Carnage\Cqorms\Entity;

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

    public function __construct($aggregateId, $aggregateType, $payload)
    {
        $this->aggregateId = $aggregateId;
        $this->payload = $payload;
        $this->aggregateType = $aggregateType;
    }

    /**
     * @return mixed
     */
    public function getPayload()
    {
        return $this->payload;
    }
}
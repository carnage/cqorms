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
     * @ORM\Column(type="object", length=255)
     */
    protected $payload;

    public function __construct($aggregateId, $payload)
    {
        $this->aggregateId = $aggregateId;
        $this->payload = $payload;
    }

    /**
     * @return mixed
     */
    public function getPayload()
    {
        return $this->payload;
    }
}
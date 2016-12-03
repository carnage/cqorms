<?php

namespace Carnage\Cqorms\Entity;

use Doctrine\ORM\Mapping as ORM;
use Carnage\Cqrs\Event\DomainMessage as DomainMessageWrapper;

/**
 * Class DomainMessage
 * @package Carnage\Cqorms\Entity
 * @ORM\Entity()
 */
class DomainMessage
{
    const AGGREGATE_ID = 'domainMessage.aggregateId';
    const AGGREGATE_TYPE = 'domainMessage.aggregateClass';
    const EVENT_CLASS = 'domainMessage.eventClass';
    /**
     * @var integer
     * @ORM\Id @ORM\GeneratedValue @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var DomainMessageWrapper
     * @ORM\Embedded(class="\Carnage\Cqrs\Event\DomainMessage", columnPrefix=false)
     */
    protected $domainMessage;

    /**
     * DomainMessageWrapper constructor.
     * @param $domainMessage
     */
    public function __construct(DomainMessageWrapper $domainMessage)
    {
        $this->domainMessage = $domainMessage;
    }

    /**
     * @return mixed
     */
    public function getPayload()
    {
        return $this->domainMessage;
    }
}
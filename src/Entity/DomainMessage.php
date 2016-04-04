<?php

namespace Carnage\Cqorms\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class DomainMessage
 * @package Carnage\Cqorms\Entity
 * @ORM\Entity()
 */
class DomainMessage
{
    const AGGREGATE_ID = 'domainMessage.aggregateId';
    const AGGREGATE_TYPE = 'domainMessage.aggregateClass';
    /**
     * @var integer
     * @ORM\Id @ORM\GeneratedValue @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var mixed
     * @ORM\Embedded(class="\Carnage\Cqrs\Event\DomainMessage", columnPrefix=false)
     */
    protected $domainMessage;

    /**
     * DomainMessage constructor.
     * @param $domainMessage
     */
    public function __construct($domainMessage)
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
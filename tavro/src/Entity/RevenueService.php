<?php

namespace App\Entity;

use Doctrine\ORM\Mapping AS ORM;

use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\MaxDepth;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Validator\Constraints as Assert;

use App\Model\Entity\Entity;
use App\Model\EntityInterface\EntityInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Repository\Entity\RevenueServiceRepository")
 * @ORM\Table(name="tavro_revenue_service", indexes={@ORM\Index(name="REVENUE_SERVICE", columns={"service_id","revenue_id"})})
 * @ExclusionPolicy("all")
 */
class RevenueService extends Entity implements EntityInterface
{
    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\Service", inversedBy="revenue_services")
     * @ORM\JoinColumn(name="service_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "detail"})
     */
    protected $service;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\Revenue", inversedBy="revenue_services")
     * @ORM\JoinColumn(name="revenue_id", referencedColumnName="id", nullable=FALSE)
     */
    protected $revenue;

    /**
     * @ORM\Column(type="integer", nullable=TRUE, options={"default" = 1})
     * @Groups({"api", "detail", "simple"})
     * @Assert\GreaterThan(
     *     value = 0,
     *     message = "Qty must be greater than 0"
     * )
     */
    protected $qty;

    public function __construct()
    {
        parent::__construct();
        $this->qty = 1;
    }

    /**
     * Set service
     *
     * @param \App\Entity\Service $service
     * @return RevenueService
     */
    public function setService(\Tavro\Entity\Service $service)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return \Tavro\Entity\Service
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set Revenue
     *
     * @param \App\Entity\Revenue $revenue
     * @return RevenueService
     */
    public function setRevenue(\Tavro\Entity\Revenue $revenue)
    {
        $this->revenue = $revenue;

        return $this;
    }

    /**
     * Get Revenue
     *
     * @return \Tavro\Entity\Revenue
     */
    public function getRevenue()
    {
        return $this->revenue;
    }


    /**
     * Set qty
     *
     * @param integer $qty
     *
     * @return RevenueService
     */
    public function setQty($qty)
    {
        $this->qty = $qty;

        return $this;
    }

    /**
     * Get qty
     *
     * @return integer
     */
    public function getQty()
    {
        return $this->qty;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return RevenueService
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }
}

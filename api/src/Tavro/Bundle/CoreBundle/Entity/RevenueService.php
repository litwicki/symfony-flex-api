<?php

namespace Tavro\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\MaxDepth;
use Doctrine\ORM\Mapping\Table;

use Tavro\Bundle\CoreBundle\Model\Entity;
use Tavro\Bundle\CoreBundle\Model\EntityInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Bundle\CoreBundle\Repository\RevenueServiceRepository")
 * @ORM\Table(name="tavro_revenue_service", indexes={@ORM\Index(name="REVENUE_SERVICE", columns={"service_id","revenue_id"})})
 * @ExclusionPolicy("all")
 */
class RevenueService extends Entity
{
    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Service", inversedBy="revenue_services")
     * @ORM\JoinColumn(name="service_id", referencedColumnName="id", nullable=false)
     * @Groups({"api", "tavro"})
     */
    protected $service;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Revenue", inversedBy="revenue_services")
     * @ORM\JoinColumn(name="revenue_id", referencedColumnName="id", nullable=false)
     */
    protected $revenue;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"default" = 1})
     * @Groups({"api", "tavro", "simple"})
     */
    protected $qty;

    public function __construct()
    {
        $this->qty = 1;
        $this->create_date = new \DateTime();
    }

    /**
     * Set service
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Service $service
     * @return RevenueService
     */
    public function setService(\Tavro\Bundle\CoreBundle\Entity\Service $service)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\Service
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set Revenue
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Revenue $revenue
     * @return RevenueService
     */
    public function setRevenue(\Tavro\Bundle\CoreBundle\Entity\Revenue $revenue)
    {
        $this->revenue = $revenue;

        return $this;
    }

    /**
     * Get Revenue
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\Revenue
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
}

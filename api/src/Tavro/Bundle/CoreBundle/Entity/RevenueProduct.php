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
 * @ORM\Table(name="tavro_revenue_product", indexes={@ORM\Index(name="REVENUE_PRODUCT", columns={"product_id","revenue_id"})})
 * @ExclusionPolicy("all")
 */
class RevenueProduct extends Entity
{
    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Product", inversedBy="revenue_products")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=false)
     * @Groups({"api", "tavro"})
     */
    protected $product;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Revenue", inversedBy="revenue_products")
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
     * Set product
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Product $product
     * @return RevenueProduct
     */
    public function setProduct(\Tavro\Bundle\CoreBundle\Entity\Product $product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set Revenue
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Revenue $revenue
     * @return RevenueProduct
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
     * @return RevenueProduct
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

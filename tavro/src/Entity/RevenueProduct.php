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
 * @ORM\Entity(repositoryClass="Tavro\Repository\Entity\RevenueProductRepository")
 * @ORM\Table(name="tavro_revenue_product", indexes={@ORM\Index(name="REVENUE_PRODUCT", columns={"product_id","revenue_id"})})
 * @ExclusionPolicy("all")
 */
class RevenueProduct extends Entity implements EntityInterface
{
    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\Product", inversedBy="revenue_products")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "detail"})
     */
    protected $product;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\Revenue", inversedBy="revenue_products")
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
     * Set product
     *
     * @param \App\Entity\Product $product
     * @return RevenueProduct
     */
    public function setProduct(\Tavro\Entity\Product $product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \Tavro\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set Revenue
     *
     * @param \App\Entity\Revenue $revenue
     * @return RevenueProduct
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

    /**
     * Set body
     *
     * @param string $body
     *
     * @return RevenueProduct
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
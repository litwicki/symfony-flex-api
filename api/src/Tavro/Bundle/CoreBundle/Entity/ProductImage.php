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
 * @ORM\Entity(repositoryClass="Tavro\Bundle\CoreBundle\Doctrine\Repository\Entity\ProductImageRepository")
 * @ORM\Table(name="tavro_product_image", indexes={@ORM\Index(name="PRODUCT_IMAGE", columns={"image_id","product_id"})})
 * @ExclusionPolicy("all")
 */
class ProductImage extends Entity implements EntityInterface
{
    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Image", inversedBy="product_images")
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id", nullable=false)
     * @Groups({"api", "tavro"})
     */
    protected $image;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Product", inversedBy="product_images")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=false)
     */
    protected $product;

    /**
     * Set image
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Image $image
     * @return ProductImage
     */
    public function setImage(\Tavro\Bundle\CoreBundle\Entity\Image $image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\Image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set Product
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Product $product
     * @return ProductImage
     */
    public function setProduct(\Tavro\Bundle\CoreBundle\Entity\Product $product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get Product
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }


    /**
     * Set body
     *
     * @param string $body
     *
     * @return ProductImage
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

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

use App\Model\Entity\Entity;
use App\Model\EntityInterface\EntityInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Repository\Entity\ProductImageRepository")
 * @ORM\Table(name="tavro_product_image", indexes={@ORM\Index(name="PRODUCT_IMAGE", columns={"image_id","product_id"})})
 * @ExclusionPolicy("all")
 */
class ProductImage extends Entity implements EntityInterface
{
    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\Image", inversedBy="product_images")
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "detail"})
     * @Expose
     */
    protected $image;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\Product", inversedBy="product_images")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=FALSE)
     * @Expose
     */
    protected $product;

    /**
     * Set image
     *
     * @param \App\Entity\Image $image
     * @return ProductImage
     */
    public function setImage(\Tavro\Entity\Image $image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \Tavro\Entity\Image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set Product
     *
     * @param \App\Entity\Product $product
     * @return ProductImage
     */
    public function setProduct(\Tavro\Entity\Product $product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get Product
     *
     * @return \Tavro\Entity\Product
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

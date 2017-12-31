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
 * @ORM\Entity(repositoryClass="Tavro\Repository\Entity\ServiceImageRepository")
 * @ORM\Table(name="tavro_service_image", indexes={@ORM\Index(name="PRODUCT_IMAGE", columns={"image_id","service_id"})})
 * @ExclusionPolicy("all")
 */
class ServiceImage extends Entity implements EntityInterface
{
    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\Image", inversedBy="service_images")
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "detail"})
     */
    protected $image;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\Service", inversedBy="service_images")
     * @ORM\JoinColumn(name="service_id", referencedColumnName="id", nullable=FALSE)
     */
    protected $service;

    /**
     * Set image
     *
     * @param \App\Entity\Image $image
     * @return ServiceImage
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
     * Set Service
     *
     * @param \App\Entity\Service $service
     * @return ServiceImage
     */
    public function setService(\Tavro\Entity\Service $service)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get Service
     *
     * @return \Tavro\Entity\Service
     */
    public function getService()
    {
        return $this->service;
    }


    /**
     * Set body
     *
     * @param string $body
     *
     * @return ServiceImage
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

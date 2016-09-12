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
 * @ORM\Entity(repositoryClass="Tavro\Bundle\CoreBundle\Repository\ServiceImageRepository")
 * @ORM\Table(name="tavro_service_image", indexes={@ORM\Index(name="PRODUCT_IMAGE", columns={"image_id","service_id"})})
 * @ExclusionPolicy("all")
 */
class ServiceImage extends Entity implements EntityInterface
{
    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Image", inversedBy="service_images")
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "tavro"})
     */
    protected $image;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Service", inversedBy="service_images")
     * @ORM\JoinColumn(name="service_id", referencedColumnName="id", nullable=FALSE)
     */
    protected $service;

    /**
     * Set image
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Image $image
     * @return ServiceImage
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
     * Set Service
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Service $service
     * @return ServiceImage
     */
    public function setService(\Tavro\Bundle\CoreBundle\Entity\Service $service)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get Service
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\Service
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

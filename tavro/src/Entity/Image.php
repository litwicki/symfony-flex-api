<?php

namespace App\Entity;

use Doctrine\ORM\Mapping AS ORM;

use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\MaxDepth;
use JMS\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping\Table;

use App\Entity\File;
use App\Model\Entity\Entity;
use App\Model\EntityInterface\AccountEntityInterface;
use App\Model\EntityInterface\S3EntityInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Repository\Entity\ImageRepository")
 * @ORM\HasLifecycleCallbacks
 * @Table(name="tavro_image")
 * @ExclusionPolicy("all")
 *
 */
class Image extends Entity implements S3EntityInterface, AccountEntityInterface
{
    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\Account", inversedBy="images")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"detail"})
     * @MaxDepth(1)
     */
    protected $account;

    /**
     * @ORM\Column(type="string", length=500, nullable=FALSE)
     * @Groups({"api", "detail"})
     * @Expose
     */
    protected $aws_url;

    /**
     * @ORM\Column(type="string", length=32, nullable=FALSE)
     * @Groups({"detail"})
     * @Expose
     */
    protected $bucket;

    /**
     * @ORM\Column(type="string", length=32, nullable=FALSE)
     * @Groups({"detail"})
     * @Expose
     */
    protected $directory;

    /**
     * @ORM\Column(type="string", length=500, nullable=FALSE)
     * @Groups({"api", "detail", "simple"})
     * @Expose
     */
    protected $aws_key;

    /**
     * @ORM\Column(type="string", length=500, nullable=FALSE)
     * @Groups({"api", "detail", "simple"})
     * @Expose
     */
    protected $original_filename;

    /**
     * @ORM\Column(type="string", length=500, nullable=FALSE)
     * @Groups({"api", "detail"})
     * @Expose
     */
    protected $mime_type;

    /**
     * @ORM\Column(type="integer", nullable=FALSE)
     * @Groups({"api", "detail"})
     * @Expose
     */
    protected $filesize;

    /**
     * @ORM\Column(type="integer", nullable=FALSE)
     * @Groups({"api", "detail"})
     * @Expose
     */
    protected $height;

    /**
     * @ORM\Column(type="integer", nullable=FALSE)
     * @Groups({"api", "detail"})
     * @Expose
     */
    protected $width;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Entity\ProductImage", mappedBy="image", cascade={"remove"})
     */
    protected $product_images;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Entity\ServiceImage", mappedBy="image", cascade={"remove"})
     */
    protected $service_images;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->product_images = new \Doctrine\Common\Collections\ArrayCollection();
        $this->service_images = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set awsUrl
     *
     * @param string $awsUrl
     *
     * @return Image
     */
    public function setAwsUrl($awsUrl)
    {
        $this->aws_url = $awsUrl;

        return $this;
    }

    /**
     * Get awsUrl
     *
     * @return string
     */
    public function getAwsUrl()
    {
        return $this->aws_url;
    }

    /**
     * Set bucket
     *
     * @param string $bucket
     *
     * @return Image
     */
    public function setBucket($bucket)
    {
        $this->bucket = $bucket;

        return $this;
    }

    /**
     * Get bucket
     *
     * @return string
     */
    public function getBucket()
    {
        return $this->bucket;
    }

    /**
     * Set directory
     *
     * @param string $directory
     *
     * @return Image
     */
    public function setDirectory($directory)
    {
        $this->directory = $directory;

        return $this;
    }

    /**
     * Get directory
     *
     * @return string
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * Set awsKey
     *
     * @param string $awsKey
     *
     * @return Image
     */
    public function setAwsKey($awsKey)
    {
        $this->aws_key = $awsKey;

        return $this;
    }

    /**
     * Get awsKey
     *
     * @return string
     */
    public function getAwsKey()
    {
        return $this->aws_key;
    }

    /**
     * Set originalFilename
     *
     * @param string $originalFilename
     *
     * @return Image
     */
    public function setOriginalFilename($originalFilename)
    {
        $this->original_filename = $originalFilename;

        return $this;
    }

    /**
     * Get originalFilename
     *
     * @return string
     */
    public function getOriginalFilename()
    {
        return $this->original_filename;
    }

    /**
     * Set mimeType
     *
     * @param string $mimeType
     *
     * @return Image
     */
    public function setMimeType($mimeType)
    {
        $this->mime_type = $mimeType;

        return $this;
    }

    /**
     * Get mimeType
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->mime_type;
    }

    /**
     * Set filesize
     *
     * @param integer $filesize
     *
     * @return Image
     */
    public function setFilesize($filesize)
    {
        $this->filesize = $filesize;

        return $this;
    }

    /**
     * Get filesize
     *
     * @return integer
     */
    public function getFilesize()
    {
        return $this->filesize;
    }

    /**
     * Set height
     *
     * @param integer $height
     *
     * @return Image
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return integer
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set width
     *
     * @param integer $width
     *
     * @return Image
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get width
     *
     * @return integer
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set account
     *
     * @param \App\Entity\Account $account
     *
     * @return Image
     */
    public function setAccount(\Tavro\Entity\Account $account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return \Tavro\Entity\Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Add productImage
     *
     * @param \App\Entity\ProductImage $productImage
     *
     * @return Image
     */
    public function addProductImage(\Tavro\Entity\ProductImage $productImage)
    {
        $this->product_images[] = $productImage;

        return $this;
    }

    /**
     * Remove productImage
     *
     * @param \App\Entity\ProductImage $productImage
     */
    public function removeProductImage(\Tavro\Entity\ProductImage $productImage)
    {
        $this->product_images->removeElement($productImage);
    }

    /**
     * Get productImages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProductImages()
    {
        return $this->product_images;
    }

    /**
     * Add serviceImage
     *
     * @param \App\Entity\ServiceImage $serviceImage
     *
     * @return Image
     */
    public function addServiceImage(\Tavro\Entity\ServiceImage $serviceImage)
    {
        $this->service_images[] = $serviceImage;

        return $this;
    }

    /**
     * Remove serviceImage
     *
     * @param \App\Entity\ServiceImage $serviceImage
     */
    public function removeServiceImage(\Tavro\Entity\ServiceImage $serviceImage)
    {
        $this->service_images->removeElement($serviceImage);
    }

    /**
     * Get serviceImages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServiceImages()
    {
        return $this->service_images;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return Image
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

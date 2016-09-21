<?php

namespace Tavro\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping\Table;

use Tavro\Bundle\CoreBundle\Model\AccountEntity;
use Tavro\Bundle\CoreBundle\Model\AccountEntityInterface;
use Tavro\Bundle\CoreBundle\Model\S3EntityInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Bundle\CoreBundle\Repository\FileRepository")
 * @ORM\HasLifecycleCallbacks
 * @Table(name="tavro_file")
 *
 */
class File extends AccountEntity implements S3EntityInterface, AccountEntityInterface
{
    /**
     * @ORM\Column(type="string", length=500, nullable=FALSE)
     * @Groups({"api", "detail"})
     */
    protected $aws_url;

    /**
     * @ORM\Column(type="string", length=32, nullable=FALSE)
     * @Groups({"detail"})
     */
    protected $bucket;

    /**
     * @ORM\Column(type="string", length=32, nullable=FALSE)
     * @Groups({"detail"})
     */
    protected $directory;

    /**
     * @ORM\Column(type="string", length=500, nullable=FALSE)
     * @Groups({"api", "detail", "simple"})
     */
    protected $aws_key;

    /**
     * @ORM\Column(type="string", length=500, nullable=FALSE)
     * @Groups({"api", "detail", "simple"})
     */
    protected $original_filename;

    /**
     * @ORM\Column(type="string", length=500, nullable=FALSE)
     * @Groups({"api", "detail"})
     */
    protected $mime_type;

    /**
     * @ORM\Column(type="integer", nullable=FALSE)
     * @Groups({"api", "detail"})
     */
    protected $filesize;


    public function __toString()
    {
        return $this->original_filename;
    }
    
    /**
     * Set aws_url
     *
     * @param string $aws_url
     * @return File
     */
    public function setPath($aws_url)
    {
        $this->aws_url = $aws_url;

        return $this;
    }

    /**
     * Get aws_url
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->aws_url;
    }

    /**
     * Set original_filename
     *
     * @param string $originalFilename
     * @return File
     */
    public function setOriginalFilename($originalFilename)
    {
        $this->original_filename = $originalFilename;

        return $this;
    }

    /**
     * Get original_filename
     *
     * @return string 
     */
    public function getOriginalFilename()
    {
        return $this->original_filename;
    }

    /**
     * Set mime_type
     *
     * @param string $mimeType
     * @return File
     */
    public function setMimeType($mimeType)
    {
        $this->mime_type = $mimeType;

        return $this;
    }

    /**
     * Get mime_type
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
     * @return File
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
     * @return File
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
     * @return File
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
     * Constructor
     */
    public function __construct()
    {
        $this->mod_images = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set bucket
     *
     * @param string $bucket
     * @return File
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
     * Set aws_url
     *
     * @param string $awsUrl
     * @return File
     */
    public function setAwsUrl($awsUrl)
    {
        $this->aws_url = $awsUrl;

        return $this;
    }

    /**
     * Get aws_url
     *
     * @return string 
     */
    public function getAwsUrl()
    {
        return $this->aws_url;
    }

    /**
     * Set aws_key
     *
     * @param string $awsKey
     * @return File
     */
    public function setAwsKey($awsKey)
    {
        $this->aws_key = $awsKey;

        return $this;
    }

    /**
     * Get aws_key
     *
     * @return string 
     */
    public function getAwsKey()
    {
        return $this->aws_key;
    }

    /**
     * Set directory
     *
     * @param string $directory
     * @return File
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
     * Set body
     *
     * @param string $body
     *
     * @return File
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

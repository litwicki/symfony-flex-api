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

use Tavro\Bundle\CoreBundle\Model\Entity;
use Tavro\Bundle\CoreBundle\Model\EntityInterface;
use Tavro\Bundle\CoreBundle\Model\S3EntityInterface;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @Table(name="tavro_file")
 *
 *
 */
class File extends Entity implements S3EntityInterface
{

    /**
     * @ORM\Column(type="string", length=500, nullable=false)
     * @Groups({"api", "tavro", "summary"})
     */
    protected $aws_url;

    /**
     * @ORM\Column(type="string", length=32, nullable=false)
     * @Groups({"api", "tavro", "summary"})
     */
    protected $bucket;

    /**
     * @ORM\Column(type="string", length=500, nullable=false)
     * @Groups({"api", "tavro", "summary"})
     */
    protected $aws_key;

    /**
     * @ORM\Column(type="string", length=500, nullable=false)
     * @Groups({"api", "tavro", "summary"})
     */
    protected $original_filename;

    /**
     * @ORM\Column(type="string", length=500, nullable=false)
     * @Groups({"api", "tavro", "summary"})
     */
    protected $mime_type;

    /**
     * @ORM\Column(type="integer", nullable=false)
     * @Groups({"api", "tavro", "summary"})
     */
    protected $filesize;

    /**
     * @ORM\Column(type="integer", nullable=false)
     * @Groups({"api", "tavro", "summary"})
     */
    protected $downloads = 0;

    /**
     * @ORM\OneToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Mod", inversedBy="file")
     * @ORM\JoinColumn(name="mod_id", referencedColumnName="id")
     */
    protected $mod;

    /**
     * Set aws_url
     *
     * @param string $aws_url
     * @return Image
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
     * Set downloads
     *
     * @param integer $downloads
     * @return File
     */
    public function setDownloads($downloads)
    {
        $this->downloads = $downloads;

        return $this;
    }

    /**
     * Get downloads
     *
     * @return integer 
     */
    public function getDownloads()
    {
        return $this->downloads;
    }

    /**
     * Set mod
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Mod $mod
     *
     * @return File
     */
    public function setMod(\Tavro\Bundle\CoreBundle\Entity\Mod $mod)
    {
        $this->mod = $mod;
        return $this;
    }

    /**
     * Get mod
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\Mod
     */
    public function getMod()
    {
        return $this->mod;
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
     * Set key
     *
     * @param string $aws_key
     * @return File
     */
    public function setAwsKey($aws_key)
    {
        $this->aws_key = $aws_key;

        return $this;
    }

    /**
     * Get key
     *
     * @return string 
     */
    public function getAwsKey()
    {
        return $this->aws_key;
    }

    /**
     * Set aws_url
     *
     * @param string $aws_url
     * @return File
     */
    public function setAwsUrl($aws_url)
    {
        $this->aws_url = $aws_url;

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

}

<?php

namespace Tavro\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\MaxDepth;
use JMS\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping\Table;

use Tavro\Bundle\CoreBundle\Model\Entity;
use Tavro\Bundle\CoreBundle\Model\AccountEntityInterface;
use Tavro\Bundle\CoreBundle\Model\S3EntityInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Bundle\CoreBundle\Repository\FileRepository")
 * @ORM\HasLifecycleCallbacks
 * @Table(name="tavro_file")
 *
 */
class File extends Entity implements S3EntityInterface, AccountEntityInterface
{
    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Account", inversedBy="files")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"detail"})
     * @MaxDepth(1)
     */
    protected $account;

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


    /**
     * Set awsUrl
     *
     * @param string $awsUrl
     *
     * @return File
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
     * Set directory
     *
     * @param string $directory
     *
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
     * Set awsKey
     *
     * @param string $awsKey
     *
     * @return File
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
     * @return File
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
     * @return File
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

    /**
     * Set account
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Account $account
     *
     * @return File
     */
    public function setAccount(\Tavro\Bundle\CoreBundle\Entity\Account $account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\Account
     */
    public function getAccount()
    {
        return $this->account;
    }
}

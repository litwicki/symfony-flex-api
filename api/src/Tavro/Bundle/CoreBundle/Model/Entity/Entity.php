<?php

namespace Tavro\Bundle\CoreBundle\Model\Entity;

use Doctrine\ORM\Mapping AS ORM;

use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\MaxDepth;

use Tavro\Bundle\CoreBundle\Entity\User;
use Tavro\Bundle\CoreBundle\Model\EntityInterface\EntityInterface;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 *
 * @ExclusionPolicy("all")
 *
 */
class Entity implements EntityInterface
{

    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    const STATUS_PENDING = 2;
    const STATUS_OTHER = 3;

    const CREATE_EVENT_CLASS = 'CreateEvent';
    const DELETE_EVENT_CLASS = 'DeleteEvent';
    const UPDATE_EVENT_CLASS = 'UpdateEvent';

    /**
     * @ORM\Id
     * @Type("integer")
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"api", "detail", "simple", "typeahead"})
     * @Expose
     */
    protected $id;

    /**
     * @Type("string")
     * @ORM\Column(type="string", length=8000, nullable=true)
     * @Groups({"api", "detail", "simple"})
     * @Expose
     */
    protected $body;

    /**
     * @Type("integer")
     * @ORM\Column(type="integer", nullable=FALSE, options={"default" = 1})
     * @Groups({"api", "detail"})
     */
    protected $status = self::STATUS_ENABLED;

    /**
     * @Type("datetime")
     * @ORM\Column(type="datetime", length=1000, nullable=FALSE)
     * @Groups({"api", "detail"})
     * @MaxDepth(3)
     */
    protected $create_date;

    /**
     * @Type("datetime")
     * @ORM\Column(type="datetime", length=1000, nullable=true)
     * @Groups({"api", "detail"})
     * @MaxDepth(3)
     */
    protected $update_date;

    public function __call($method, $args)
    {

    }

    public function __construct()
    {
        $this->create_date = new \DateTime();
        $this->update_date = new \DateTime();
        $this->status = self::STATUS_ENABLED;
    }

    /**
     * Decrypts the string with a 2 way hash.
     */
    protected function decrypt($string, $password = 'QWSFUD5TeCVwy4TJ2obaY5ghrOA6jd1K')
    {
        return openssl_decrypt($string, "AES-256-CBC", $password, 0, '5728582182961174');
    }

    public static function staticEncrypt($string, $password = 'QWSFUD5TeCVwy4TJ2obaY5ghrOA6jd1K')
    {
        return openssl_encrypt($string, "AES-256-CBC", $password, 0, '5728582182961174');
    }

    /**
     * Return a string representation of the Entity.
     */
    public function __toString()
    {
        $class = new \ReflectionClass($this);
        $className = $class->getName();

        if(property_exists($className, 'title')) {
            return $this->title;
        }
        elseif(property_exists($className, 'name')) {
            return $this->name;
        }
        elseif(property_exists($className, 'slug')) {
            return $this->slug;
        }
        else {
            return sprintf('%s %s', $class->getShortName(), $this->id);
        }

    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set create_date
     *
     * @param \DateTime $createDate
     * @return $this
     */
    public function setCreateDate($createDate)
    {
        $this->create_date = $createDate;

        return $this;
    }

    /**
     * Get create_date
     *
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->create_date;
    }

    /**
     * Set update_date
     *
     * @param \DateTime $updateDate
     * @return $this
     */
    public function setUpdateDate($updateDate)
    {
        $this->update_date = $updateDate;

        return $this;
    }

    /**
     * Get update_date
     *
     * @return \DateTime
     */
    public function getUpdateDate()
    {
        return $this->update_date;
    }

    /**
     * Automatically set the create_date and last_update_date on persist.
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->update_date = new \DateTime("now");
        $this->create_date = new \DateTime("now");
        $this->status = self::STATUS_ENABLED;
    }

    public function getSerializedUserId()
    {
        return method_exists($this, 'getUser') ? $this->getUser()->getId() : 0;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return $this
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
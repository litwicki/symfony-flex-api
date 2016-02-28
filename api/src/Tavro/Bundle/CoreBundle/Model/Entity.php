<?php

namespace Tavro\Bundle\CoreBundle\Model;

use Doctrine\ORM\Mapping AS ORM;

use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\Groups;

use Tavro\Bundle\CoreBundle\Entity\User;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Entity implements EntityInterface
{

    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    const STATUS_PENDING = 2;
    const STATUS_OTHER = 3;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"api", "tavro", "simple", "typeahead"})
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=8000, nullable=true)
     * @Groups({"api", "tavro", "simple"})
     */
    protected $body;

    /**
     * @ORM\Column(type="integer", nullable=false)
     * @Groups({"api", "tavro", "simple"})
     */
    protected $status = self::STATUS_ENABLED;

    /**
     * @ORM\Column(type="datetime", length=1000, nullable=false)
     * @Groups({"api", "tavro", "simple"})
     */
    protected $create_date;

    /**
     * @ORM\Column(type="datetime", length=1000, nullable=true)
     * @Groups({"api", "tavro", "simple"})
     */
    protected $update_date;

    public function __call($method, $args)
    {

    }

    public function __construct()
    {
        $this->create_date = new \DateTime('now');
        $this->update_date = new \DateTime('now');
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

        /**
         * Last resort...
         */
        $string = sprintf('%s %s', $class->getShortName(), $this->id);
        return $string;
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
    }

    public function getSerializedUserId()
    {
        return property_exists($this, 'user') ? $this->user->getId() : 0;
    }

}
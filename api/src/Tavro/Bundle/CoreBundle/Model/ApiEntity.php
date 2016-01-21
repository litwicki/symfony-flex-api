<?php

namespace Tavro\Bundle\CoreBundle\Model;

use Doctrine\ORM\Mapping AS ORM;
use Gedmo\Mapping\Annotation as Gedmo;
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
class ApiEntity extends Entity implements ApiEntityInterface
{

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     * @Groups({"api", "tavro", "simple"})
     */
    protected $title;

    /**
     * @Gedmo\Slug(fields={"id", "title"}, updatable=true, dateFormat="Ymd/")
     * @ORM\Column(type="string", length=64, nullable=false, unique=true)
     * @Groups({"api", "tavro", "simple"})
     */
    protected $slug;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\User")
     * @ORM\JoinColumn(name="updated_by_user_id", referencedColumnName="id")
     * @Groups({"api", "tavro"})
     */
    protected $updated_by;

    /**
     * Set slug
     *
     * @param string $slug
     * @return $this
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set updated_by
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\User $updatedBy
     * @return EntityInterface
     */
    public function setUpdatedBy(\Tavro\Bundle\CoreBundle\Entity\User $updatedBy = null)
    {
        $this->updated_by = $updatedBy;

        return $this;
    }

    /**
     * Get updated_by
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\User
     */
    public function getUpdatedBy()
    {
        return $this->updated_by;
    }

}
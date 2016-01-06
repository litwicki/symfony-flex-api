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
     * @Gedmo\Slug(fields={"id", "title"}, updatable=true, dateFormat="Ymd/")
     * @ORM\Column(type="string", length=64, nullable=false, unique=true)
     * @Groups({"api", "tavro", "summary"})
     */
    protected $slug;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\User")
     * @ORM\JoinColumn(name="removed_by_user_id", referencedColumnName="id")
     * @Groups({"api", "tavro"})
     */
    protected $removed_by;

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
     * Set removed_by
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\User $removedBy
     * @return EntityInterface
     */
    public function setRemovedBy(\Tavro\Bundle\CoreBundle\Entity\User $removedBy = null)
    {
        $this->removed_by = $removedBy;

        return $this;
    }

    /**
     * Get removed_by
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\User
     */
    public function getRemovedBy()
    {
        return $this->removed_by;
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
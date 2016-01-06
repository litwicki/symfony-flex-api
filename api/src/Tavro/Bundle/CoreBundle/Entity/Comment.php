<?php

namespace Tavro\Bundle\CoreBundle\Entity;

use Tavro\Bundle\CoreBundle\Model\ApiEntityInterface;
use Doctrine\ORM\Mapping AS ORM;

use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping\Table;
use JMS\Serializer\Annotation\MaxDepth;

use Tavro\Bundle\CoreBundle\Model\ApiEntity;
use Tavro\Bundle\CoreBundle\Model\EntityInterface;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @Table(name="tavro_comment")
 */
class Comment extends ApiEntity
{
    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     * @Groups({"api", "tavro", "summary"})
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length=8000, nullable=true)
     * @Groups({"api", "tavro", "summary"})
     */
    protected $body;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\NodeComment", mappedBy="comment", cascade={"remove"})
     */
    protected $node_comments;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\ModComment", mappedBy="comment", cascade={"remove"})
     */
    protected $mod_comments;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\User", inversedBy="comments")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @Groups({"api", "tavro", "summary"})
     * @MaxDepth(2)
     */
    protected $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->node_comments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->status = 1;
        $this->create_date = new \DateTime();
    }

    public function __toString()
    {
        return $this->title;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Comment
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
     * Set body
     *
     * @param string $body
     * @return Comment
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
     * Add node_comments
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\NodeComment $nodeComments
     * @return Comment
     */
    public function addNodeComment(\Tavro\Bundle\CoreBundle\Entity\NodeComment $nodeComments)
    {
        $this->node_comments[] = $nodeComments;

        return $this;
    }

    /**
     * Remove node_comments
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\NodeComment $nodeComments
     */
    public function removeNodeComment(\Tavro\Bundle\CoreBundle\Entity\NodeComment $nodeComments)
    {
        $this->node_comments->removeElement($nodeComments);
    }

    /**
     * Get node_comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNodeComments()
    {
        return $this->node_comments;
    }

    /**
     * Set user
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     * @return Comment
     */
    public function setUser(\Tavro\Bundle\CoreBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add mod_comments
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\ModComment $modComments
     * @return Comment
     */
    public function addModComment(\Tavro\Bundle\CoreBundle\Entity\ModComment $modComments)
    {
        $this->mod_comments[] = $modComments;

        return $this;
    }

    /**
     * Remove mod_comments
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\ModComment $modComments
     */
    public function removeModComment(\Tavro\Bundle\CoreBundle\Entity\ModComment $modComments)
    {
        $this->mod_comments->removeElement($modComments);
    }

    /**
     * Get mod_comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getModComments()
    {
        return $this->mod_comments;
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
     * @return Comment
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
     * @return Comment
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
     * @return Comment
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

}

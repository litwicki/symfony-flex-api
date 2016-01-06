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
use JMS\Serializer\Annotation\MaxDepth;
use JMS\Serializer\Annotation\SerializedName;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Validator\Constraints as Assert;

use Tavro\Bundle\CoreBundle\Model\ApiEntity;
use Tavro\Bundle\CoreBundle\Model\EntityInterface;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="tavro_node", indexes={@ORM\Index(name="NODE_TYPE", columns={"type"})})
 *
 */
class Node extends ApiEntity
{

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Choice(
     *      choices = { "guide", "node", "page", "article", "wiki"},
     *      message = "Choose a valid node type."
     * )
     * @Groups({"api", "tavro", "summary"})
     */
    protected $type;

    /**
     * @ORM\Column(type="string", length=500, nullable=false)
     * @Groups({"api", "tavro", "summary"})
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length=8000, nullable=true)
     * @Groups({"api", "tavro", "summary"})
     */
    protected $body;

    /**
     * @ORM\Column(type="datetime", length=1000, nullable=true)
     * @Groups({"api", "tavro", "summary"})
     */
    protected $display_date;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"default" = 0})
     * @Groups({"api", "tavro", "summary"})
     */
    protected $views;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\User", inversedBy="nodes")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     * @Groups({"api", "tavro", "summary"})
     * @MaxDepth(3)
     */
    protected $user;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\NodeComment", mappedBy="node", cascade={"remove"})
     */
    protected $node_comments;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\NodeTag", mappedBy="node", cascade={"remove"})
     */
    protected $node_tags;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\NodeRead", mappedBy="node", cascade={"remove"})
     */
    protected $nodes_read;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Organization", inversedBy="nodes")
     * @ORM\JoinColumn(name="organization_id", referencedColumnName="id", nullable=false)
     * @Groups({"api", "tavro", "summary"})
     * @MaxDepth(3)
     */
    protected $organization;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->node_tags = new \Doctrine\Common\Collections\ArrayCollection();;
        $this->node_comments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->nodes_read = new \Doctrine\Common\Collections\ArrayCollection();
        $this->status = 1;
        $now = new \DateTime();
        $tz = new \DateTimeZone('America/New_York');
        $now->setTimezone($tz);
        $this->create_date = $now;
        $this->views = 0;

        $this->display_date = new \DateTime();
    }

    public function __toString()
    {
        return $this->title;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Node
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Node
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
     * @return Node
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
     * Set user
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     * @return Node
     */
    public function setUser(\Tavro\Bundle\CoreBundle\Entity\User $user)
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
     * Set organization
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Organization $organization
     * @return Node
     */
    public function setOrganization(\Tavro\Bundle\CoreBundle\Entity\Organization $organization)
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * Get organization
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\Organization
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * Set display_date
     *
     * @param \DateTime $displayDate
     * @return Node
     */
    public function setDisplayDate($displayDate)
    {
        $this->display_date = $displayDate;

        return $this;
    }

    /**
     * Get display_date
     *
     * @return \DateTime
     */
    public function getDisplayDate()
    {
        return $this->display_date;
    }

    /**
     * Add images
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Node $images
     * @return Node
     */
    public function addImage(\Tavro\Bundle\CoreBundle\Entity\Node $images)
    {
        $this->images[] = $images;

        return $this;
    }

    /**
     * Remove images
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Node $images
     */
    public function removeImage(\Tavro\Bundle\CoreBundle\Entity\Node $images)
    {
        $this->images->removeElement($images);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages()
    {
        return $this->images;
    }


    /**
     * Add node_comments
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\NodeComment $nodeComments
     * @return Node
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
     * Add nodes_read
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\NodeRead $nodesRead
     * @return Node
     */
    public function addNodesRead(\Tavro\Bundle\CoreBundle\Entity\NodeRead $nodesRead)
    {
        $this->nodes_read[] = $nodesRead;

        return $this;
    }

    /**
     * Remove nodes_read
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\NodeRead $nodesRead
     */
    public function removeNodesRead(\Tavro\Bundle\CoreBundle\Entity\NodeRead $nodesRead)
    {
        $this->nodes_read->removeElement($nodesRead);
    }

    /**
     * Get nodes_read
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNodesRead()
    {
        return $this->nodes_read;
    }

    /**
     * Set views
     *
     * @param integer $views
     * @return Node
     */
    public function setViews($views)
    {
        $this->views = $views;

        return $this;
    }

    /**
     * Get views
     *
     * @return integer
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * @VirtualProperty
     * @SerializedName("node_comments")
     * @Groups({"api", "tavro","detail"})
     * @MaxDepth(2)
     */
    public function getComments()
    {
        $comments = new \Doctrine\Common\Collections\ArrayCollection();
        foreach($this->node_comments as $nodeComment) {
            $comments->add($nodeComment->getComment());
        }
        return $comments;
    }

    /**
     * @VirtualProperty
     * @SerializedName("read_count")
     * @Groups({"api", "tavro", "summary"})
     * @return int
     */
    public function getReadCount()
    {
        return $this->nodes_read->count();
    }

    /**
     * @VirtualProperty
     * @SerializedName("comment_count")
     * @Groups({"api", "tavro", "summary"})
     * @return int
     */
    public function getCommentCount()
    {
        return $this->node_comments->count();
    }

    /**
     * @VirtualProperty
     * @SerializedName("node_tags")
     * @Groups({"api", "tavro","detail"})
     * @MaxDepth(2)
     */
    public function getTags()
    {
        $tags = new \Doctrine\Common\Collections\ArrayCollection();
        foreach($this->node_tags as $nodeTag) {
            $tags->add($nodeTag->getTag());
        }
        return $tags;
    }
    
    /**
     * Add node_tags
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\NodeTag $nodeTags
     * @return Node
     */
    public function addNodeTag(\Tavro\Bundle\CoreBundle\Entity\NodeTag $nodeTags)
    {
        $this->node_tags[] = $nodeTags;

        return $this;
    }

    /**
     * Remove node_tags
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\NodeTag $nodeTags
     */
    public function removeNodeTag(\Tavro\Bundle\CoreBundle\Entity\NodeTag $nodeTags)
    {
        $this->node_tags->removeElement($nodeTags);
    }

    /**
     * Get node_tags
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNodeTags()
    {
        return $this->node_tags;
    }

}

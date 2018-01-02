<?php
namespace App\Entity;

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

use App\Model\Entity\Entity;
use App\Model\EntityInterface\AccountEntityInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Repository\AccountEntity\NodeRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="tavro_node", indexes={@ORM\Index(name="NODE_TYPE", columns={"type"})})
 * @ExclusionPolicy("all")
 *
 */
class Node extends Entity implements AccountEntityInterface
{

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\Account", inversedBy="nodes")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"detail"})
     * @MaxDepth(1)
     * @Expose
     */
    protected $account;

    /**
     * @ORM\Column(type="string", length=255, nullable=FALSE)
     * @Groups({"api", "detail", "simple"})
     * @Expose
     */
    protected $title;

    /**
     * @ORM\Column(type="string", nullable=TRUE)
     * @Groups({"api", "detail", "simple"})
     * @Expose
     */
    protected $type;

    /**
     * @ORM\Column(type="datetime", length=1000, nullable=TRUE)
     * @Groups({"api", "detail", "simple"})
     * @Expose
     */
    protected $display_date;

    /**
     * @ORM\Column(type="integer", nullable=TRUE, options={"default" = 0})
     * @Groups({"api", "detail", "simple"})
     * @Assert\GreaterThanOrEqual(
     *     value = 0,
     *     message = "Views must be greater than 0"
     * )
     * @Expose
     */
    protected $views;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\User", inversedBy="nodes")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "detail", "simple"})
     * @MaxDepth(1)
     * @Expose
     */
    protected $user;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Entity\NodeComment", mappedBy="node", cascade={"remove"})
     */
    protected $node_comments;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Entity\NodeTag", mappedBy="node", cascade={"remove"})
     */
    protected $node_tags;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Entity\NodeRead", mappedBy="node", cascade={"remove"})
     */
    protected $nodes_read;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->node_tags = new \Doctrine\Common\Collections\ArrayCollection();
        $this->node_comments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->nodes_read = new \Doctrine\Common\Collections\ArrayCollection();
        $this->status = self::STATUS_ENABLED;
        $this->create_date = new \DateTime();
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
     * Set user
     *
     * @param \App\Entity\User $user
     * @return Node
     */
    public function setUser(\Tavro\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Tavro\Entity\User
     */
    public function getUser()
    {
        return $this->user;
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
     * @param \App\Entity\Node $images
     * @return Node
     */
    public function addImage(\Tavro\Entity\Node $images)
    {
        $this->images[] = $images;

        return $this;
    }

    /**
     * Remove images
     *
     * @param \App\Entity\Node $images
     */
    public function removeImage(\Tavro\Entity\Node $images)
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
     * @param \App\Entity\NodeComment $nodeComments
     * @return Node
     */
    public function addNodeComment(\Tavro\Entity\NodeComment $nodeComments)
    {
        $this->node_comments[] = $nodeComments;

        return $this;
    }

    /**
     * Remove node_comments
     *
     * @param \App\Entity\NodeComment $nodeComments
     */
    public function removeNodeComment(\Tavro\Entity\NodeComment $nodeComments)
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
     * @param \App\Entity\NodeRead $nodesRead
     * @return Node
     */
    public function addNodesRead(\Tavro\Entity\NodeRead $nodesRead)
    {
        $this->nodes_read[] = $nodesRead;

        return $this;
    }

    /**
     * Remove nodes_read
     *
     * @param \App\Entity\NodeRead $nodesRead
     */
    public function removeNodesRead(\Tavro\Entity\NodeRead $nodesRead)
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
     * @SerializedName("comments")
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
     * @Groups({"api", "detail", "simple"})
     * @return int
     */
    public function getReadCount()
    {
        return $this->nodes_read->count();
    }

    /**
     * @VirtualProperty
     * @SerializedName("comment_count")
     * @Groups({"api", "detail", "simple"})
     * @return int
     */
    public function getCommentCount()
    {
        return $this->node_comments->count();
    }

    /**
     * @VirtualProperty
     * @SerializedName("tags")
     * @Groups({"api", "detail","detail"})
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
     * @param \App\Entity\NodeTag $nodeTags
     * @return Node
     */
    public function addNodeTag(\Tavro\Entity\NodeTag $nodeTags)
    {
        $this->node_tags[] = $nodeTags;

        return $this;
    }

    /**
     * Remove node_tags
     *
     * @param \App\Entity\NodeTag $nodeTags
     */
    public function removeNodeTag(\Tavro\Entity\NodeTag $nodeTags)
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


    /**
     * Set title
     *
     * @param string $title
     *
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
     * Set account
     *
     * @param \App\Entity\Account $account
     *
     * @return Node
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
}
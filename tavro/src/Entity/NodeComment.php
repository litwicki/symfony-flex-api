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
use Doctrine\ORM\Mapping\Table;

use App\Model\Entity\Entity;
use App\Model\EntityInterface\EntityInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Repository\Entity\NodeCommentRepository")
 * @ORM\Table(name="tavro_node_comment", indexes={@ORM\Index(name="NODE_COMMENT", columns={"comment_id","node_id"})})
 * @ExclusionPolicy("all")
 */
class NodeComment extends Entity implements EntityInterface
{
    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\Comment", inversedBy="node_comments")
     * @ORM\JoinColumn(name="comment_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "detail"})
     */
    protected $comment;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\Node", inversedBy="node_comments")
     * @ORM\JoinColumn(name="node_id", referencedColumnName="id", nullable=FALSE)
     */
    protected $node;

    public function __construct()
    {
        parent::__construct();
        $this->create_date = new \DateTime();
    }

    /**
     * Set comment
     *
     * @param \App\Entity\Comment $comment
     * @return NodeComment
     */
    public function setComment(\Tavro\Entity\Comment $comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return \Tavro\Entity\Comment
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set Node
     *
     * @param \App\Entity\Node $node
     * @return NodeComment
     */
    public function setNode(\Tavro\Entity\Node $node)
    {
        $this->node = $node;

        return $this;
    }

    /**
     * Get Node
     *
     * @return \Tavro\Entity\Node
     */
    public function getNode()
    {
        return $this->node;
    }


    /**
     * Set body
     *
     * @param string $body
     *
     * @return NodeComment
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

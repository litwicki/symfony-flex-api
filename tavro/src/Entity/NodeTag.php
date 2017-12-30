<?php

namespace Tavro\Entity;

use Doctrine\ORM\Mapping AS ORM;

use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\MaxDepth;
use Doctrine\ORM\Mapping\Table;

use Tavro\Model\Entity\Entity;
use Tavro\Model\EntityInterface\EntityInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Repository\Entity\NodeTagRepository")
 * @ORM\Table(name="tavro_node_tag", indexes={@ORM\Index(name="NODE_TAG", columns={"tag_id","node_id"})})
 * @ExclusionPolicy("all")
 */
class NodeTag extends Entity implements EntityInterface
{
    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\Tag", inversedBy="node_tags")
     * @ORM\JoinColumn(name="tag_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "detail"})
     */
    protected $tag;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\Node", inversedBy="node_tags")
     * @ORM\JoinColumn(name="node_id", referencedColumnName="id", nullable=FALSE)
     */
    protected $node;

    /**
     * Set tag
     *
     * @param \Tavro\Entity\Tag $tag
     * @return NodeTag
     */
    public function setTag(\Tavro\Entity\Tag $tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return \Tavro\Entity\Tag
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Set Node
     *
     * @param \Tavro\Entity\Node $node
     * @return NodeTag
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
     * @return NodeTag
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

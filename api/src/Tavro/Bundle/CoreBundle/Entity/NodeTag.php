<?php

namespace Tavro\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\MaxDepth;
use Doctrine\ORM\Mapping\Table;

use Tavro\Bundle\CoreBundle\Model\Entity;
use Tavro\Bundle\CoreBundle\Model\EntityInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Bundle\CoreBundle\Repository\NodeTagRepository")
 * @ORM\Table(name="tavro_node_tag", indexes={@ORM\Index(name="NODE_TAG", columns={"tag_id","node_id"})})
 * @ExclusionPolicy("all")
 */
class NodeTag extends Entity
{
    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Tag", inversedBy="node_tags")
     * @ORM\JoinColumn(name="tag_id", referencedColumnName="id", nullable=false)
     * @Groups({"api", "tavro"})
     */
    protected $tag;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Node", inversedBy="node_tags")
     * @ORM\JoinColumn(name="node_id", referencedColumnName="id", nullable=false)
     */
    protected $node;

    /**
     * Set tag
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Tag $tag
     * @return NodeTag
     */
    public function setTag(\Tavro\Bundle\CoreBundle\Entity\Tag $tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\Tag
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Set Node
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Node $node
     * @return NodeTag
     */
    public function setNode(\Tavro\Bundle\CoreBundle\Entity\Node $node)
    {
        $this->node = $node;

        return $this;
    }

    /**
     * Get Node
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\Node
     */
    public function getNode()
    {
        return $this->node;
    }

}

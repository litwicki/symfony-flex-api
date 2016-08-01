<?php

namespace Tavro\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping\Table;

use Tavro\Bundle\CoreBundle\Model\Entity;
use Tavro\Bundle\CoreBundle\Model\EntityInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Bundle\CoreBundle\Doctrine\Repository\Entity\NodeReadRepository")
 * @ORM\Table(name="tavro_node_read", indexes={@ORM\Index(name="USER_NODE_READ", columns={"user_id","node_id"})})
 */
class NodeRead extends Entity implements EntityInterface
{

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\User", inversedBy="nodes_read")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Node", inversedBy="nodes_read",)
     * @ORM\JoinColumn(name="node_id", referencedColumnName="id", nullable=false)
     */
    protected $node;

    /**
     * Set user
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     * @return nodeRead
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
     * Set node
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\node $node
     * @return nodeRead
     */
    public function setNode(\Tavro\Bundle\CoreBundle\Entity\Node $node)
    {
        $this->node = $node;

        return $this;
    }

    /**
     * Get node
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\Node
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
     * @return NodeRead
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

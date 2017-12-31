<?php

namespace App\Entity;

use Doctrine\ORM\Mapping AS ORM;

use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping\Table;

use App\Model\Entity\Entity;
use App\Model\EntityInterface\EntityInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Repository\Entity\NodeReadRepository")
 * @ORM\Table(name="tavro_node_read", uniqueConstraints={@ORM\UniqueConstraint(name="USER_NODE_READ", columns={"user_id", "node_id"})})
 * @ExclusionPolicy("all")
 */
class NodeRead extends Entity implements EntityInterface
{

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\User", inversedBy="nodes_read")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=FALSE)
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\Node", inversedBy="nodes_read",)
     * @ORM\JoinColumn(name="node_id", referencedColumnName="id", nullable=FALSE)
     */
    protected $node;

    /**
     * Set user
     *
     * @param \App\Entity\User $user
     * @return nodeRead
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
     * Set node
     *
     * @param \App\Entity\node $node
     * @return nodeRead
     */
    public function setNode(\Tavro\Entity\Node $node)
    {
        $this->node = $node;

        return $this;
    }

    /**
     * Get node
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

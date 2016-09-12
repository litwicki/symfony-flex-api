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
 * @ORM\Entity(repositoryClass="Tavro\Bundle\CoreBundle\Repository\RevenueCommentRepository")
 * @ORM\Table(name="tavro_revenue_comment", indexes={@ORM\Index(name="NODE_COMMENT", columns={"comment_id","revenue_id"})})
 * @ExclusionPolicy("all")
 */
class RevenueComment extends Entity implements EntityInterface
{
    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Comment", inversedBy="revenue_comments")
     * @ORM\JoinColumn(name="comment_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "tavro"})
     */
    protected $comment;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Revenue", inversedBy="revenue_comments")
     * @ORM\JoinColumn(name="revenue_id", referencedColumnName="id", nullable=FALSE)
     */
    protected $revenue;

    /**
     * Set comment
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Comment $comment
     * @return RevenueComment
     */
    public function setComment(\Tavro\Bundle\CoreBundle\Entity\Comment $comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\Comment
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set Revenue
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Revenue $revenue
     * @return RevenueComment
     */
    public function setRevenue(\Tavro\Bundle\CoreBundle\Entity\Revenue $revenue)
    {
        $this->revenue = $revenue;

        return $this;
    }

    /**
     * Get Revenue
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\Revenue
     */
    public function getRevenue()
    {
        return $this->revenue;
    }


    /**
     * Set body
     *
     * @param string $body
     *
     * @return RevenueComment
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

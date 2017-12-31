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
 * @ORM\Entity(repositoryClass="Tavro\Repository\Entity\RevenueCommentRepository")
 * @ORM\Table(name="tavro_revenue_comment", indexes={@ORM\Index(name="NODE_COMMENT", columns={"comment_id","revenue_id"})})
 * @ExclusionPolicy("all")
 */
class RevenueComment extends Entity implements EntityInterface
{
    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\Comment", inversedBy="revenue_comments")
     * @ORM\JoinColumn(name="comment_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "detail"})
     */
    protected $comment;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\Revenue", inversedBy="revenue_comments")
     * @ORM\JoinColumn(name="revenue_id", referencedColumnName="id", nullable=FALSE)
     */
    protected $revenue;

    /**
     * Set comment
     *
     * @param \App\Entity\Comment $comment
     * @return RevenueComment
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
     * Set Revenue
     *
     * @param \App\Entity\Revenue $revenue
     * @return RevenueComment
     */
    public function setRevenue(\Tavro\Entity\Revenue $revenue)
    {
        $this->revenue = $revenue;

        return $this;
    }

    /**
     * Get Revenue
     *
     * @return \Tavro\Entity\Revenue
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

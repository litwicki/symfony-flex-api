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
 * @ORM\Table(name="tavro_funding_comment", indexes={@ORM\Index(name="NODE_COMMENT", columns={"comment_id","funding_id"})})
 * @ExclusionPolicy("all")
 */
class FundingComment extends Entity
{
    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Comment", inversedBy="funding_comments")
     * @ORM\JoinColumn(name="comment_id", referencedColumnName="id", nullable=false)
     * @Groups({"api", "tavro"})
     */
    protected $comment;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Funding", inversedBy="funding_comments")
     * @ORM\JoinColumn(name="funding_id", referencedColumnName="id", nullable=false)
     */
    protected $funding;

    /**
     * Set comment
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Comment $comment
     * @return FundingComment
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
     * Set Funding
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Funding $funding
     * @return FundingComment
     */
    public function setFunding(\Tavro\Bundle\CoreBundle\Entity\Funding $funding)
    {
        $this->funding = $funding;

        return $this;
    }

    /**
     * Get Funding
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\Funding
     */
    public function getFunding()
    {
        return $this->funding;
    }

}

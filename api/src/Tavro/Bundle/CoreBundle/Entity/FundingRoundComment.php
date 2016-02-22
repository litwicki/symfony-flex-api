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
 * @ORM\Entity(repositoryClass="Tavro\Bundle\CoreBundle\Repository\FundingRoundCommentRepository")
 * @ORM\Table(name="tavro_funding_round_comment", indexes={@ORM\Index(name="FUNDING_ROUND_COMMENT", columns={"comment_id","funding_round_id"})})
 * @ExclusionPolicy("all")
 */
class FundingRoundComment extends Entity
{
    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Comment", inversedBy="funding_round_comments")
     * @ORM\JoinColumn(name="comment_id", referencedColumnName="id", nullable=false)
     * @Groups({"api", "tavro"})
     */
    protected $comment;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\FundingRound", inversedBy="funding_round_comments")
     * @ORM\JoinColumn(name="funding_round_id", referencedColumnName="id", nullable=false)
     */
    protected $funding_round;

    /**
     * Set comment
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Comment $comment
     * @return FundingRoundComment
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
     * Set FundingRound
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\FundingRound $funding_round
     * @return FundingRoundComment
     */
    public function setFundingRound(\Tavro\Bundle\CoreBundle\Entity\FundingRound $funding_round)
    {
        $this->funding_round = $funding_round;

        return $this;
    }

    /**
     * Get FundingRound
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\FundingRound
     */
    public function getFundingRound()
    {
        return $this->funding_round;
    }

}

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
 * @ORM\Entity(repositoryClass="Tavro\Repository\Entity\FundingRoundCommentRepository")
 * @ORM\Table(name="tavro_funding_round_comment", indexes={@ORM\Index(name="FUNDING_ROUND_COMMENT", columns={"comment_id","funding_round_id"})})
 * @ExclusionPolicy("all")
 */
class FundingRoundComment extends Entity implements EntityInterface
{
    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\Comment", inversedBy="funding_round_comments")
     * @ORM\JoinColumn(name="comment_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "detail"})
     */
    protected $comment;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\FundingRound", inversedBy="funding_round_comments")
     * @ORM\JoinColumn(name="funding_round_id", referencedColumnName="id", nullable=FALSE)
     */
    protected $funding_round;

    /**
     * Set comment
     *
     * @param \App\Entity\Comment $comment
     * @return FundingRoundComment
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
     * Set FundingRound
     *
     * @param \App\Entity\FundingRound $funding_round
     * @return FundingRoundComment
     */
    public function setFundingRound(\Tavro\Entity\FundingRound $funding_round)
    {
        $this->funding_round = $funding_round;

        return $this;
    }

    /**
     * Get FundingRound
     *
     * @return \Tavro\Entity\FundingRound
     */
    public function getFundingRound()
    {
        return $this->funding_round;
    }


    /**
     * Set body
     *
     * @param string $body
     *
     * @return FundingRoundComment
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

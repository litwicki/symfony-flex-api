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

use Tavro\Bundle\CoreBundle\Model\Entity\Entity;
use Tavro\Bundle\CoreBundle\Model\EntityInterface\EntityInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Bundle\CoreBundle\Repository\Entity\FundingRoundShareholderRepository")
 * @ORM\Table(name="tavro_funding_round_shareholder", indexes={@ORM\Index(name="FUNDING_ROUND_SHAREHOLDER", columns={"shareholder_id","funding_round_id"})})
 * @ExclusionPolicy("all")
 */
class FundingRoundShareholder extends Entity implements EntityInterface
{
    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Shareholder", inversedBy="funding_round_shareholders")
     * @ORM\JoinColumn(name="shareholder_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "detail"})
     * @MaxDepth(3)
     */
    protected $shareholder;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\FundingRound", inversedBy="funding_round_shareholders")
     * @ORM\JoinColumn(name="funding_round_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "detail"})
     * @MaxDepth(1)
     */
    protected $funding_round;

    /**
     * @ORM\Column(type="integer", nullable=TRUE, options={"default" = 0})
     * @Groups({"api", "detail", "simple"})
     */
    protected $shares;

    /**
     * Set shareholder
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Shareholder $shareholder
     * @return FundingRoundShareholder
     */
    public function setShareholder(\Tavro\Bundle\CoreBundle\Entity\Shareholder $shareholder)
    {
        $this->shareholder = $shareholder;

        return $this;
    }

    /**
     * Get shareholder
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\Shareholder
     */
    public function getShareholder()
    {
        return $this->shareholder;
    }

    /**
     * Set FundingRound
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\FundingRound $funding_round
     * @return FundingRoundShareholder
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


    /**
     * Set shares
     *
     * @param integer $shares
     *
     * @return FundingRoundShareholder
     */
    public function setShares($shares)
    {
        $this->shares = $shares;

        return $this;
    }

    /**
     * Get shares
     *
     * @return integer
     */
    public function getShares()
    {
        return $this->shares;
    }


    /**
     * Set body
     *
     * @param string $body
     *
     * @return FundingRoundShareholder
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

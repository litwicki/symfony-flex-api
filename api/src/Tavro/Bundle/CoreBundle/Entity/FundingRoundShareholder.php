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
 * @ORM\Entity(repositoryClass="Tavro\Bundle\CoreBundle\Repository\FundingRoundShareholderRepository")
 * @ORM\Table(name="tavro_funding_round_shareholder", indexes={@ORM\Index(name="ORGANIZATION_SHAREHOLDER", columns={"shareholder_id","funding_round_id"})})
 */
class FundingRoundShareholder extends Entity implements EntityInterface
{
    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Shareholder", inversedBy="funding_round_shareholders")
     * @ORM\JoinColumn(name="shareholder_id", referencedColumnName="id", nullable=false)
     * @Groups({"api", "tavro"})
     * @MaxDepth(3)
     */
    protected $shareholder;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\FundingRound", inversedBy="funding_round_shareholders")
     * @ORM\JoinColumn(name="funding_round_id", referencedColumnName="id", nullable=false)
     * @Groups({"api", "tavro"})
     * @MaxDepth(3)
     */
    protected $funding_round;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"default" = 0})
     * @Groups({"api", "tavro", "simple"})
     */
    protected $shares;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Organization", inversedBy="funding_round_shareholders")
     * @ORM\JoinColumn(name="organization_id", referencedColumnName="id", nullable=false)
     * @Groups({"api", "tavro"})
     * @MaxDepth(3)
     */
    protected $organization;

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
     * Set organization
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Organization $organization
     *
     * @return FundingRoundShareholder
     */
    public function setOrganization(\Tavro\Bundle\CoreBundle\Entity\Organization $organization)
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * Get organization
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\Organization
     */
    public function getOrganization()
    {
        return $this->organization;
    }
}

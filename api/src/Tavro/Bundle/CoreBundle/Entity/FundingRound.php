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
use JMS\Serializer\Annotation\SerializedName;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Validator\Constraints as Assert;

use Tavro\Bundle\CoreBundle\Model\OrganizationEntity;
use Tavro\Bundle\CoreBundle\Model\OrganizationEntityInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Bundle\CoreBundle\Doctrine\Repository\Entity\FundingRoundRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="tavro_funding_round")
 *
 */
class FundingRound extends OrganizationEntity implements OrganizationEntityInterface
{
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"api", "tavro", "simple"})
     */
    protected $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"api", "tavro", "simple"})
     */
    protected $prospectus;

    /**
     * @ORM\Column(type="float", nullable=true, options={"default" = 0})
     * @Groups({"api", "tavro", "simple"})
     */
    protected $share_price;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"default" = 0})
     * @Groups({"api", "tavro", "simple"})
     */
    protected $total_shares;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\FundingRoundShareholder", mappedBy="funding_round", cascade={"remove"})
     * @Groups({"tavro"})
     * @MaxDepth(1)
     */
    protected $funding_round_shareholders;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\FundingRoundComment", mappedBy="funding_round", cascade={"remove"})
     */
    protected $funding_round_comments;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->status = 1;
        $now = new \DateTime();
        $tz = new \DateTimeZone('America/New_York');
        $now->setTimezone($tz);
        $this->create_date = $now;
        $this->funding_round_shareholders = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return $this
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

    /**
     * Set type
     *
     * @param string $type
     *
     * @return FundingRound
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set prospectus
     *
     * @param string $prospectus
     *
     * @return FundingRound
     */
    public function setProspectus($prospectus)
    {
        $this->prospectus = $prospectus;

        return $this;
    }

    /**
     * Get prospectus
     *
     * @return string
     */
    public function getProspectus()
    {
        return $this->prospectus;
    }

    /**
     * Set sharePrice
     *
     * @param float $sharePrice
     *
     * @return FundingRound
     */
    public function setSharePrice($sharePrice)
    {
        $this->share_price = $sharePrice;

        return $this;
    }

    /**
     * Get sharePrice
     *
     * @return float
     */
    public function getSharePrice()
    {
        return $this->share_price;
    }

    /**
     * Set totalShares
     *
     * @param integer $totalShares
     *
     * @return FundingRound
     */
    public function setTotalShares($totalShares)
    {
        $this->total_shares = $totalShares;

        return $this;
    }

    /**
     * Get totalShares
     *
     * @return integer
     */
    public function getTotalShares()
    {
        return $this->total_shares;
    }

    /**
     * Add fundingRoundShareholder
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\FundingRoundShareholder $fundingRoundShareholder
     *
     * @return FundingRound
     */
    public function addFundingRoundShareholder(\Tavro\Bundle\CoreBundle\Entity\FundingRoundShareholder $fundingRoundShareholder)
    {
        $this->funding_round_shareholders[] = $fundingRoundShareholder;

        return $this;
    }

    /**
     * Remove fundingRoundShareholder
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\FundingRoundShareholder $fundingRoundShareholder
     */
    public function removeFundingRoundShareholder(\Tavro\Bundle\CoreBundle\Entity\FundingRoundShareholder $fundingRoundShareholder)
    {
        $this->funding_round_shareholders->removeElement($fundingRoundShareholder);
    }

    /**
     * Get fundingRoundShareholders
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFundingRoundShareholders()
    {
        return $this->funding_round_shareholders;
    }

    /**
     * Add fundingRoundComment
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\FundingRoundComment $fundingRoundComment
     *
     * @return FundingRound
     */
    public function addFundingRoundComment(\Tavro\Bundle\CoreBundle\Entity\FundingRoundComment $fundingRoundComment)
    {
        $this->funding_round_comments[] = $fundingRoundComment;

        return $this;
    }

    /**
     * Remove fundingRoundComment
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\FundingRoundComment $fundingRoundComment
     */
    public function removeFundingRoundComment(\Tavro\Bundle\CoreBundle\Entity\FundingRoundComment $fundingRoundComment)
    {
        $this->funding_round_comments->removeElement($fundingRoundComment);
    }

    /**
     * Get fundingRoundComments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFundingRoundComments()
    {
        return $this->funding_round_comments;
    }

    /**
     * @VirtualProperty
     * @SerializedName("shareholders")
     * @Groups({"api", "tavro"})
     * @MaxDepth(2)
     */
    public function getShareholders()
    {
        $items = new \Doctrine\Common\Collections\ArrayCollection();
        foreach($this->funding_round_shareholders as $entity) {
            $items->add($entity->getShareholder());
        }
        return $items;
    }
}

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

use Tavro\Bundle\CoreBundle\Model\Entity;
use Tavro\Bundle\CoreBundle\Model\EntityInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Bundle\CoreBundle\Repository\FundingRoundCommentRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="tavro_funding_round")
 *
 */
class FundingRound extends Entity implements EntityInterface
{
    /**
     * @ORM\Column(type="string", length=8000, nullable=true)
     * @Groups({"api", "tavro", "simple"})
     */
    protected $body;

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
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Organization", inversedBy="funding_rounds")
     * @ORM\JoinColumn(name="organization_id", referencedColumnName="id", nullable=false)
     * @Groups({"api", "tavro"})
     * @MaxDepth(3)
     */
    protected $organization;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\FundingRoundShareholder", mappedBy="funding_round", cascade={"remove"})
     * @Groups({"tavro"})
     * @MaxDepth(3)
     */
    protected $funding_round_shareholders;

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
        $this->shareholders = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return Service
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
     * Set organization
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Organization $organization
     * @return Service
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

    public function getShareholders()
    {
        $items = array();
        foreach($this->funding_round_shareholders as $entity) {
            $items[] = $entity->getShareholder();
        }
        return $items;
    }
}

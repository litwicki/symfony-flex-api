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
use JMS\Serializer\Annotation\SerializedName;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Validator\Constraints as Assert;

use App\Model\Entity\Entity;
use App\Model\EntityInterface\AccountEntityInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Repository\AccountEntity\FundingRoundRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="tavro_funding_round")
 * @ExclusionPolicy("all")
 *
 */
class FundingRound extends Entity implements AccountEntityInterface
{
    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\Account", inversedBy="funding_rounds")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"detail"})
     * @MaxDepth(1)
     * @Expose
     */
    protected $account;

    /**
     * @ORM\Column(type="string", length=255, nullable=TRUE)
     * @Groups({"api", "detail", "simple"})
     * @Expose
     */
    protected $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=TRUE)
     * @Groups({"api", "detail", "simple"})
     * @Expose
     */
    protected $prospectus;

    /**
     * @ORM\Column(type="float", nullable=TRUE, options={"default" = 0})
     * @Groups({"api", "detail", "simple"})
     * @Assert\GreaterThanOrEqual(
     *     value = 0,
     *     message = "Share Price must be greater than 0"
     * )
     * @Expose
     */
    protected $share_price;

    /**
     * @ORM\Column(type="integer", nullable=TRUE, options={"default" = 0})
     * @Groups({"api", "detail", "simple"})
     * @Assert\GreaterThanOrEqual(
     *     value = 0,
     *     message = "Number of shares must be greater than 0"
     * )
     * @Expose
     */
    protected $total_shares;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Entity\FundingRoundShareholder", mappedBy="funding_round", cascade={"remove"})
     */
    protected $funding_round_shareholders;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Entity\FundingRoundComment", mappedBy="funding_round", cascade={"remove"})
     */
    protected $funding_round_comments;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->status = self::STATUS_ENABLED;
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
     * @param \App\Entity\FundingRoundShareholder $fundingRoundShareholder
     *
     * @return FundingRound
     */
    public function addFundingRoundShareholder(\Tavro\Entity\FundingRoundShareholder $fundingRoundShareholder)
    {
        $this->funding_round_shareholders[] = $fundingRoundShareholder;

        return $this;
    }

    /**
     * Remove fundingRoundShareholder
     *
     * @param \App\Entity\FundingRoundShareholder $fundingRoundShareholder
     */
    public function removeFundingRoundShareholder(\Tavro\Entity\FundingRoundShareholder $fundingRoundShareholder)
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
     * @param \App\Entity\FundingRoundComment $fundingRoundComment
     *
     * @return FundingRound
     */
    public function addFundingRoundComment(\Tavro\Entity\FundingRoundComment $fundingRoundComment)
    {
        $this->funding_round_comments[] = $fundingRoundComment;

        return $this;
    }

    /**
     * Remove fundingRoundComment
     *
     * @param \App\Entity\FundingRoundComment $fundingRoundComment
     */
    public function removeFundingRoundComment(\Tavro\Entity\FundingRoundComment $fundingRoundComment)
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
     * @Groups({"api", "detail"})
     * @MaxDepth(3)
     */
    public function getShareholders()
    {
        $items = new \Doctrine\Common\Collections\ArrayCollection();
        foreach($this->funding_round_shareholders as $entity) {
            $items->add([
                'shares' => $entity->getShares(),
                'shareholder' => $entity->getShareholder()
            ]);
        }
        return $items;
    }

    /**
     * Set account
     *
     * @param \App\Entity\Account $account
     *
     * @return FundingRound
     */
    public function setAccount(\Tavro\Entity\Account $account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return \Tavro\Entity\Account
     */
    public function getAccount()
    {
        return $this->account;
    }
}

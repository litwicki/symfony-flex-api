<?php
namespace Tavro\Bundle\CoreBundle\Entity;

use Tavro\Bundle\CoreBundle\Model\ApiEntityInterface;
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
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="tavro_funding_round")
 *
 */
class FundingRound extends Entity
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
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\Shareholder", mappedBy="funding_round", cascade={"remove"})
     * @Groups({"tavro"})
     * @MaxDepth(3)
     */
    protected $shareholders;

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
     * Add shareholder
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Shareholder $shareholder
     *
     * @return Shareholder
     */
    public function addShareholder(\Tavro\Bundle\CoreBundle\Entity\Shareholder $shareholder)
    {
        $this->shareholders[] = $shareholder;

        return $this;
    }

    /**
     * Remove shareholder
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Shareholder $shareholder
     */
    public function removeShareholder(\Tavro\Bundle\CoreBundle\Entity\Shareholder $shareholder)
    {
        $this->shareholders->removeElement($shareholder);
    }

    /**
     * Get shareholders
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getShareholders()
    {
        return $this->shareholders;
    }
}

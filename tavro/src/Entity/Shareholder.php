<?php

namespace App\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Rhumsaa\Uuid\Uuid;

use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\XmlRoot;
use JMS\Serializer\Annotation\XmlNamespace;
use JMS\Serializer\Annotation\SerializedName;
use Doctrine\ORM\Mapping\Table;

use Symfony\Component\Validator\Constraints as Assert;

use App\Model\Entity\Entity;
use App\Model\EntityInterface\EntityInterface;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Repository\Entity\ShareholderRepository")
 * @Table(name="tavro_shareholder")
 *
 * @XmlRoot("shareholder")
 * @XmlNamespace(uri="http://tavro.io/api/shareholders")
 * @ExclusionPolicy("all")
 */
class Shareholder extends Entity implements EntityInterface
{
    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\Person")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "detail", "simple"})
     * @MaxDepth(3)
     * @Expose
     */
    protected $person;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Entity\FundingRoundShareholder", mappedBy="shareholder", cascade={"remove"})
     * @Groups({"detail"})
     * @MaxDepth(1)
     * @Expose
     */
    protected $funding_round_shareholders;

    /**
     * Set person
     *
     * @param \App\Entity\Person $person
     *
     * @return Shareholder
     */
    public function setPerson(\Tavro\Entity\Person $person)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person
     *
     * @return \Tavro\Entity\Person
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Add fundingRoundShareholder
     *
     * @param \App\Entity\FundingRoundShareholder $fundingRoundShareholder
     *
     * @return Shareholder
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
     * Set body
     *
     * @param string $body
     *
     * @return Shareholder
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

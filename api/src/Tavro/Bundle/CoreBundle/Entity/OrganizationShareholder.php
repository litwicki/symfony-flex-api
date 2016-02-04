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
 * @ORM\Table(name="tavro_organization_shareholder", indexes={@ORM\Index(name="ORGANIZATION_SHAREHOLDER", columns={"shareholder_id","organization_id"})})
 */
class OrganizationShareholder extends Entity
{
    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Shareholder", inversedBy="organization_shareholders")
     * @ORM\JoinColumn(name="shareholder_id", referencedColumnName="id", nullable=false)
     * @Groups({"api", "tavro"})
     * @MaxDepth(3)
     */
    protected $shareholder;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Organization", inversedBy="organization_shareholders")
     * @ORM\JoinColumn(name="organization_id", referencedColumnName="id", nullable=false)
     * @Groups({"api", "tavro"})
     * @MaxDepth(3)
     */
    protected $organization;

    /**
     * Set shareholder
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Shareholder $shareholder
     * @return OrganizationShareholder
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
     * Set Organization
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Organization $organization
     * @return OrganizationShareholder
     */
    public function setOrganization(\Tavro\Bundle\CoreBundle\Entity\Organization $organization)
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * Get Organization
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\Organization
     */
    public function getOrganization()
    {
        return $this->organization;
    }

}

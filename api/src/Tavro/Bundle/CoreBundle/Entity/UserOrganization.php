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
 * @ORM\Entity(repositoryClass="Tavro\Bundle\CoreBundle\Doctrine\Repository\Entity\UserOrganizationRepository")
 * @ORM\Table(name="tavro_user_organization", indexes={@ORM\Index(name="USER_ORGANIZATION", columns={"organization_id","user_id"})})
 */
class UserOrganization extends Entity implements EntityInterface
{
    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Organization", inversedBy="user_organizations")
     * @ORM\JoinColumn(name="organization_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "tavro"})
     * @MaxDepth(3)
     */
    protected $organization;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\User", inversedBy="user_organizations")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "tavro"})
     * @MaxDepth(3)
     */
    protected $user;

    /**
     * @ORM\Column(type="string", length=255, nullable=TRUE)
     * @Groups({"api", "tavro", "simple"})
     */
    protected $org_title;

    /**
     * Set organization
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Organization $organization
     * @return UserOrganization
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
     * Set User
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     * @return UserOrganization
     */
    public function setUser(\Tavro\Bundle\CoreBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get User
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }


    /**
     * Set body
     *
     * @param string $body
     *
     * @return UserOrganization
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
     * Set orgTitle
     *
     * @param string $orgTitle
     *
     * @return UserOrganization
     */
    public function setOrgTitle($orgTitle)
    {
        $this->org_title = $orgTitle;

        return $this;
    }

    /**
     * Get orgTitle
     *
     * @return string
     */
    public function getOrgTitle()
    {
        return $this->org_title;
    }
}

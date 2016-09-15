<?php

namespace Tavro\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping\Table;
use JMS\Serializer\Annotation\MaxDepth;

use Tavro\Bundle\CoreBundle\Model\Entity;
use Tavro\Bundle\CoreBundle\Model\EntityInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Bundle\CoreBundle\Repository\AccountRepository")
 * @ORM\HasLifecycleCallbacks
 * @Table(name="tavro_account")
 */
class Account extends Entity implements EntityInterface
{
    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\User", inversedBy="nodes")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "tavro", "simple"})
     * @MaxDepth(1)
     */
    protected $user;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\AccountUser", mappedBy="account", cascade={"remove"})
     */
    protected $account_users;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\Organization", mappedBy="account", cascade={"remove"})
     */
    protected $organizations;

    /**
     * @ORM\Column(type="string", unique=TRUE, length=500, nullable=FALSE)
     * @Groups({"api", "tavro", "simple"})
     */
    protected $name;

    /**
     * @ORM\Column(type="string", unique=TRUE, length=500, nullable=FALSE)
     * @Groups({"api", "tavro", "simple"})
     */
    protected $name_clean;

    /**
     * Set body
     *
     * @param string $body
     *
     * @return Account
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
     * Add accountUser
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\AccountUser $accountUser
     *
     * @return Account
     */
    public function addAccountUser(\Tavro\Bundle\CoreBundle\Entity\AccountUser $accountUser)
    {
        $this->account_users[] = $accountUser;

        return $this;
    }

    /**
     * Remove accountUser
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\AccountUser $accountUser
     */
    public function removeAccountUser(\Tavro\Bundle\CoreBundle\Entity\AccountUser $accountUser)
    {
        $this->account_users->removeElement($accountUser);
    }

    /**
     * Get accountUsers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAccountUsers()
    {
        return $this->account_users;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Account
     */
    public function setName($name)
    {
        $this->name = $name;

        $clean = str_replace(' ', '-', $name);
        $clean = str_replace('_', '-', $name);
        $clean = strtolower($clean);
        $clean = preg_replace('/[^A-Za-z0-9-]/', '', $clean);

        $this->setNameClean($clean);

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set nameClean
     *
     * @param string $nameClean
     *
     * @return Account
     */
    public function setNameClean($nameClean)
    {
        $this->name_clean = $nameClean;

        return $this;
    }

    /**
     * Get nameClean
     *
     * @return string
     */
    public function getNameClean()
    {
        return $this->name_clean;
    }

    /**
     * Set user
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     *
     * @return Account
     */
    public function setUser(\Tavro\Bundle\CoreBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Get All the User entities associated via AccountUsers.
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getUsers()
    {
        $users = new \Doctrine\Common\Collections\ArrayCollection();
        foreach($this->account_users as $entity) {
            $users->add($entity->getUser());
        }
        return $users;

    }

    /**
     * Add organization
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Organization $organization
     *
     * @return Account
     */
    public function addOrganization(\Tavro\Bundle\CoreBundle\Entity\Organization $organization)
    {
        $this->organizations[] = $organization;

        return $this;
    }

    /**
     * Remove organization
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Organization $organization
     */
    public function removeOrganization(\Tavro\Bundle\CoreBundle\Entity\Organization $organization)
    {
        $this->organizations->removeElement($organization);
    }

    /**
     * Get organizations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrganizations()
    {
        return $this->organizations;
    }
}

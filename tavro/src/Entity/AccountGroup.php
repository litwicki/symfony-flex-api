<?php

namespace App\Entity;

use Doctrine\ORM\Mapping AS ORM;

use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping\Table;
use JMS\Serializer\Annotation\MaxDepth;

use App\Model\Entity\Entity;
use App\Model\EntityInterface\AccountEntityInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Repository\AccountEntity\AccountGroupRepository")
 * @ORM\HasLifecycleCallbacks
 * @Table(name="tavro_account_group")
 * @ExclusionPolicy("all")
 */
class AccountGroup extends Entity implements AccountEntityInterface
{
    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\Account", inversedBy="account_groups")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"detail"})
     * @MaxDepth(1)
     * @Expose
     */
    protected $account;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\User", inversedBy="account_groups")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "detail", "simple"})
     * @MaxDepth(1)
     * @Expose
     */
    protected $user;

    /**
     * @ORM\Column(type="string", unique=TRUE, length=500, nullable=FALSE)
     * @Groups({"api", "detail", "simple"})
     * @Expose
     */
    protected $name;

    /**
     * @ORM\Column(type="string", unique=TRUE, length=500, nullable=FALSE)
     * @Groups({"api", "detail", "simple"})
     * @Expose
     */
    protected $name_clean;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Entity\AccountGroupUser", mappedBy="account_group")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    protected $account_group_users;

    /**
     * Automatically set the create_date and last_update_date on persist.
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->setNameClean(Account::createCleanName($this->getName()));

        if(is_null($this->create_date)) {
            $this->create_date = new \DateTime();
        }

        $this->update_date = new \DateTime();
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return AccountGroup
     */
    public function setName($name)
    {
        $this->name = $name;

        $clean = Account::createCleanName($name);

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
     * @return AccountGroup
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
     * Set body
     *
     * @param string $body
     *
     * @return AccountGroup
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
     * Set user
     *
     * @param \App\Entity\User $user
     *
     * @return AccountGroup
     */
    public function setUser(\Tavro\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Tavro\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set account
     *
     * @param \App\Entity\Account $account
     *
     * @return AccountGroup
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

    /**
     * Add accountGroupUser
     *
     * @param \App\Entity\AccountGroupUser $accountGroupUser
     *
     * @return AccountGroup
     */
    public function addAccountGroupUser(\Tavro\Entity\AccountGroupUser $accountGroupUser)
    {
        $this->account_group_users[] = $accountGroupUser;

        return $this;
    }

    /**
     * Remove accountGroupUser
     *
     * @param \App\Entity\AccountGroupUser $accountGroupUser
     */
    public function removeAccountGroupUser(\Tavro\Entity\AccountGroupUser $accountGroupUser)
    {
        $this->account_group_users->removeElement($accountGroupUser);
    }

    /**
     * Get accountGroupUsers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAccountGroupUsers()
    {
        return $this->account_group_users;
    }
}

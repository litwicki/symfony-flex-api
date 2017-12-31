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
use Doctrine\ORM\Mapping\Table;

use App\Model\Entity\Entity;
use App\Model\EntityInterface\AccountEntityInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Repository\AccountEntity\AccountUserRepository")
 * @ORM\Table(name="tavro_account_user", uniqueConstraints={@ORM\UniqueConstraint(name="account_user", columns={"account_id", "user_id"})})
 * @ExclusionPolicy("all")
 *
 */
class AccountUser extends Entity implements AccountEntityInterface
{
    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\Account", inversedBy="account_users")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "detail"})
     * @MaxDepth(3)
     */
    protected $account;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\User", inversedBy="account_users")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "detail"})
     * @MaxDepth(3)
     */
    protected $user;

    /**
     * @ORM\Column(type="boolean", nullable=FALSE)
     * @Groups({"api", "detail"})
     * @Expose
     */
    protected $is_primary;

    public function __construct()
    {
        parent::__construct();
        $this->is_primary = false;
    }

    /**
     * Set account
     *
     * @param \App\Entity\Account $account
     * @return AccountUser
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
     * Set User
     *
     * @param \App\Entity\User $user
     * @return AccountUser
     */
    public function setUser(\Tavro\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get User
     *
     * @return \Tavro\Entity\User
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
     * @return AccountUser
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
     * Set isPrimary
     *
     * @param boolean $isPrimary
     *
     * @return AccountUser
     */
    public function setIsPrimary($isPrimary)
    {
        $this->is_primary = $isPrimary;

        return $this;
    }

    /**
     * Get isPrimary
     *
     * @return boolean
     */
    public function getIsPrimary()
    {
        return $this->is_primary;
    }
}

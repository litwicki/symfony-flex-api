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

use Tavro\Bundle\CoreBundle\Model\Entity\Entity;
use Tavro\Bundle\CoreBundle\Model\EntityInterface\EntityInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Bundle\CoreBundle\Repository\Entity\AccountGroupUserRepository")
 * @ORM\Table(name="tavro_account_group_user", uniqueConstraints={@ORM\UniqueConstraint(name="ACCOUNT_GROUP_USER", columns={"user_id", "account_group_id"})})
 * @ExclusionPolicy("all")
 * @ExclusionPolicy("all")
 */
class AccountGroupUser extends Entity implements EntityInterface
{
    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\User", inversedBy="account_group_users")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "detail"})
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\AccountGroup", inversedBy="account_group_users")
     * @ORM\JoinColumn(name="account_group_id", referencedColumnName="id", nullable=FALSE)
     */
    protected $account_group;

    /**
     * Set user
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     * @return AccountGroupUser
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
     * Set AccountGroup
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\AccountGroup $account_group
     * @return AccountGroupUser
     */
    public function setAccountGroup(\Tavro\Bundle\CoreBundle\Entity\AccountGroup $account_group)
    {
        $this->account_group = $account_group;

        return $this;
    }

    /**
     * Get AccountGroup
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\AccountGroup
     */
    public function getAccountGroup()
    {
        return $this->account_group;
    }


    /**
     * Set body
     *
     * @param string $body
     *
     * @return AccountGroupUser
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

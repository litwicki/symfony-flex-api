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
 * @ORM\Entity(repositoryClass="Tavro\Bundle\CoreBundle\Repository\Entity\AccountTypeRepository")
 * @ORM\Table(name="tavro_account_type")
 * @ExclusionPolicy("all")
 */
class AccountType extends Entity implements EntityInterface
{

    /**
     * @ORM\Column(type="string", length=255, nullable=FALSE)
     * @Groups({"api", "detail", "simple"})
     * @Expose
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\Account", mappedBy="type")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    protected $accounts;

    /**
     * Set name
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

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
     * Add account
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Account $account
     *
     * @return AccountType
     */
    public function addAccount(\Tavro\Bundle\CoreBundle\Entity\Account $account)
    {
        $this->accounts[] = $account;

        return $this;
    }

    /**
     * Remove account
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Account $account
     */
    public function removeAccount(\Tavro\Bundle\CoreBundle\Entity\Account $account)
    {
        $this->accounts->removeElement($account);
    }

    /**
     * Get accounts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAccounts()
    {
        return $this->accounts;
    }
}

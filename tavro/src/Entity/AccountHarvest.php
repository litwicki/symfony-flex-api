<?php
namespace Tavro\Entity;

use Doctrine\ORM\Mapping AS ORM;

use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\MaxDepth;
use JMS\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping\Table;

use Tavro\Model\Entity\Entity;
use Tavro\Model\EntityInterface\AccountEntityInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Repository\Entity\AccountHarvestRepository")
 * @Table(name="tavro_account_harvest")
 * @ExclusionPolicy("all")
 */
class AccountHarvest extends Entity implements AccountEntityInterface
{

    /**
     * @ORM\OneToOne(targetEntity="Tavro\Entity\Account", inversedBy="account_harvest")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "detail", "simple"})
     * @MaxDepth(1)
     * @Expose
     */
    public $account;

    /**
     * @ORM\Column(type="string", unique=TRUE, nullable=FALSE)
     * @Groups({"api", "simple", "detail"})
     * @Expose
     */
    protected $harvest_email;

    /**
     * @ORM\Column(type="string", unique=TRUE, nullable=FALSE)
     * @Groups({"api", "simple", "detail"})
     * @Expose
     */
    protected $harvest_password;


    /**
     * Set harvestEmail
     *
     * @param string $harvestEmail
     *
     * @return AccountHarvest
     */
    public function setHarvestEmail($harvestEmail)
    {
        $this->harvest_email = self::staticEncrypt($harvestEmail, $this->getAccount()->getGuid());

        return $this;
    }

    /**
     * Get harvestEmail
     *
     * @return string
     */
    public function getHarvestEmail()
    {
        if($this->getAccount() instanceof Account) {
            return self::decrypt($this->harvest_email, $this->getAccount()->getGuid());
        }
        return null;
    }

    /**
     * Set harvestPassword
     *
     * @param string $harvestPassword
     *
     * @return AccountHarvest
     */
    public function setHarvestPassword($harvestPassword)
    {
        $this->harvest_password = self::staticEncrypt($harvestPassword, $this->getAccount()->getGuid());

        return $this;
    }

    /**
     * Get harvestPassword
     *
     * @return string
     */
    public function getHarvestPassword()
    {
        if($this->getAccount() instanceof Account) {
            return self::decrypt($this->harvest_password, $this->getAccount()->getGuid());
        }
        return null;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return AccountHarvest
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
     * Set account
     *
     * @param \Tavro\Entity\Account $account
     *
     * @return AccountHarvest
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

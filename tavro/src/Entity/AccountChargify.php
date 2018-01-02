<?php
namespace App\Entity;

use Doctrine\ORM\Mapping AS ORM;

use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\MaxDepth;
use JMS\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping\Table;

use App\Model\Entity\Entity;
use App\Model\EntityInterface\AccountEntityInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Repository\Entity\AccountChargifyRepository")
 * @Table(name="tavro_account_chargify")
 * @ExclusionPolicy("all")
 */
class AccountChargify extends Entity implements AccountEntityInterface
{

    /**
     * @ORM\OneToOne(targetEntity="Tavro\Entity\Account", inversedBy="account_chargify")
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
    protected $api_key;

    /**
     * @ORM\Column(type="string", unique=TRUE, nullable=FALSE)
     * @Groups({"api", "simple", "detail"})
     * @Expose
     */
    protected $api_password;

    public function __construct()
    {
        parent::__construct();
        $this->setApiPassword('x');
    }


    /**
     * Set apiKey
     *
     * @param string $apiKey
     *
     * @return AccountChargify
     */
    public function setApiKey($apiKey)
    {
        if($this->getAccount() instanceof Account) {
            $this->api_key = self::staticEncrypt($apiKey, $this->getAccount()->getGuid());
        }

        return $this;
    }

    /**
     * Get apiKey
     *
     * @return string
     */
    public function getApiKey()
    {
        if($this->getAccount() instanceof Account) {
            return self::decrypt($this->api_key, $this->getAccount()->getGuid());
        }
        return null;
    }

    /**
     * Set apiPassword
     *
     * @param string $apiPassword
     *
     * @return AccountChargify
     */
    public function setApiPassword($apiPassword)
    {
        if($this->getAccount() instanceof Account) {
            $this->api_password = self::staticEncrypt($apiPassword, $this->getAccount()->getGuid());
        }
        return $this;
    }

    /**
     * Get apiPassword
     *
     * @return string
     */
    public function getApiPassword()
    {
        if($this->getAccount() instanceof Account) {
            return self::decrypt($this->api_password, $this->getAccount()->getGuid());
        }
        return null;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return AccountChargify
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
     * @param \App\Entity\Account $account
     *
     * @return AccountChargify
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
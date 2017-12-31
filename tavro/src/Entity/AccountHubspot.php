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
 * @ORM\Entity(repositoryClass="Tavro\Repository\Entity\AccountHubspotRepository")
 * @Table(name="tavro_account_hubspot")
 * @ExclusionPolicy("all")
 */
class AccountHubspot extends Entity implements AccountEntityInterface
{
    /**
     * @ORM\OneToOne(targetEntity="Tavro\Entity\Account", inversedBy="account_hubspot")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "detail", "simple"})
     * @MaxDepth(1)
     */
    public $account;

    /**
     * @ORM\Column(type="integer", unique=TRUE, nullable=FALSE)
     * @Groups({"api", "simple", "detail"})
     * @Expose
     */
    protected $hubspot_id;

    /**
     * @ORM\Column(type="string", unique=TRUE, nullable=FALSE)
     * @Groups({"api", "simple", "detail"})
     * @Expose
     */
    protected $api_key;


    /**
     * Set hubspotId
     *
     * @param integer $hubspotId
     *
     * @return AccountHubspot
     */
    public function setHubspotId($hubspotId)
    {
        $this->hubspot_id = self::staticEncrypt($hubspotId, $this->getAccount()->getGuid());

        return $this;
    }

    /**
     * Get hubspotId
     *
     * @return integer
     */
    public function getHubspotId()
    {
        if($this->getAccount() instanceof Account) {
            return self::decrypt($this->hubspot_id, $this->getAccount()->getGuid());
        }
        return null;
    }

    /**
     * Set apiKey
     *
     * @param string $apiKey
     *
     * @return AccountHubspot
     */
    public function setApiKey($apiKey)
    {
        $this->api_key = self::staticEncrypt($apiKey, $this->getAccount()->getGuid());

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
     * Set body
     *
     * @param string $body
     *
     * @return AccountHubspot
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
     * @return AccountHubspot
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

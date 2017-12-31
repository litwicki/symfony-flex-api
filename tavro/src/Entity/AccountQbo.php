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
 * @ORM\Entity(repositoryClass="Tavro\Repository\Entity\AccountQboRepository")
 * @Table(name="tavro_account_qbo")
 * @ExclusionPolicy("all")
 */
class AccountQbo extends Entity implements AccountEntityInterface
{

    /**
     * @ORM\OneToOne(targetEntity="Tavro\Entity\Account", inversedBy="account_qbo")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "detail", "simple"})
     * @MaxDepth(1)
     */
    public $account;

    /**
     * @ORM\Column(type="string", unique=TRUE, nullable=TRUE)
     * @Groups({"api", "simple", "detail"})
     * @Expose
     */
    protected $consumer_key;

    /**
     * @ORM\Column(type="string", unique=TRUE, nullable=TRUE)
     * @Groups({"api", "simple", "detail"})
     * @Expose
     */
    protected $consumer_secret;

    /**
     * @ORM\Column(type="string", unique=TRUE, nullable=TRUE)
     * @Groups({"api", "simple", "detail"})
     * @Expose
     */
    protected $access_token;

    /**
     * @ORM\Column(type="string", unique=TRUE, nullable=TRUE)
     * @Groups({"api", "simple", "detail"})
     * @Expose
     */
    protected $access_token_secret;

    /**
     * @ORM\Column(type="integer", unique=TRUE, nullable=TRUE)
     * @Groups({"api", "simple", "detail"})
     * @Expose
     */
    protected $realm_id;


    /**
     * Set consumerKey
     *
     * @param string $consumerKey
     *
     * @return AccountQbo
     */
    public function setConsumerKey($consumerKey)
    {
        if($this->getAccount() instanceof Account) {
            $this->consumer_key = self::staticEncrypt($consumerKey, $this->getAccount()->getGuid());
        }

        return $this;
    }

    /**
     * Get consumerKey
     *
     * @return string
     */
    public function getConsumerKey()
    {
        if($this->getAccount() instanceof Account) {
            return $this->consumer_key = self::decrypt($this->consumer_key, $this->getAccount()->getGuid());
        }
        return null;
    }

    /**
     * Set consumerSecret
     *
     * @param string $consumerSecret
     *
     * @return AccountQbo
     */
    public function setConsumerSecret($consumerSecret)
    {
        if($this->getAccount() instanceof Account) {
            $this->consumer_secret = self::staticEncrypt($consumerSecret, $this->getAccount()->getGuid());
        }

        return $this;
    }

    /**
     * Get consumerSecret
     *
     * @return string
     */
    public function getConsumerSecret()
    {
        if($this->getAccount() instanceof Account) {
            return $this->consumer_secret = self::decrypt($this->consumer_secret, $this->getAccount()->getGuid());
        }
        return null;
    }

    /**
     * Set accessToken
     *
     * @param string $accessToken
     *
     * @return AccountQbo
     */
    public function setAccessToken($accessToken)
    {
        if($this->getAccount() instanceof Account) {
            $this->access_token = self::staticEncrypt($accessToken, $this->getAccount()->getGuid());
        }

        return $this;
    }

    /**
     * Get accessToken
     *
     * @return string
     */
    public function getAccessToken()
    {
        if($this->getAccount() instanceof Account) {
            return $this->access_token = self::decrypt($this->access_token, $this->getAccount()->getGuid());
        }

        return null;
    }

    /**
     * Set accessTokenSecret
     *
     * @param string $accessTokenSecret
     *
     * @return AccountQbo
     */
    public function setAccessTokenSecret($accessTokenSecret)
    {
        if($this->getAccount() instanceof Account) {
            $this->access_token_secret = self::staticEncrypt($accessTokenSecret, $this->getAccount()->getGuid());
        }

        return $this;
    }

    /**
     * Get accessTokenSecret
     *
     * @return string
     */
    public function getAccessTokenSecret()
    {
        if($this->getAccount() instanceof Account) {
            return $this->access_token_secret = self::decrypt($this->access_token_secret, $this->getAccount()->getGuid());
        }
        return null;
    }

    /**
    * Set realmId
    *
    * @param integer $realmId
    *
    * @return AccountQbo
    */
    public function setRealmId($realmId)
    {
        if($this->getAccount() instanceof Account) {
            $this->realm_id = self::staticEncrypt($realmId, $this->getAccount()->getGuid());
        }

        return $this;
    }

    /**
     * Get realmId
     *
     * @return AccountQbo
     */
    public function getRealmId()
    {
        if($this->getAccount() instanceof Account) {
            return $this->realm_id = self::decrypt($this->realm_id, $this->getAccount()->getGuid());
        }
        return null;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return AccountQbo
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
     * @return AccountQbo
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

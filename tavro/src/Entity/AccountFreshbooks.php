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
 * @ORM\Entity(repositoryClass="Tavro\Repository\Entity\AccountFreshbooksRepository")
 * @Table(name="tavro_account_freshbooks")
 * @ExclusionPolicy("all")
 */
class AccountFreshbooks extends Entity implements AccountEntityInterface
{

    /**
     * @ORM\OneToOne(targetEntity="Tavro\Entity\Account", inversedBy="account_freshbooks")
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
    protected $auth_token;


    /**
     * Set authToken
     *
     * @param string $authToken
     *
     * @return AccountFreshbooks
     */
    public function setAuthToken($authToken)
    {
        if($this->getAccount() instanceof Account) {
            $this->auth_token = self::staticEncrypt($authToken, $this->getAccount()->getGuid());
        }

        return $this;
    }

    /**
     * Get authToken
     *
     * @return string
     */
    public function getAuthToken()
    {
        if($this->getAccount() instanceof Account) {
            return self::decrypt($this->auth_token, $this->getAccount()->getGuid());
        }
        return null;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return AccountFreshbooks
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
     * @return AccountFreshbooks
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

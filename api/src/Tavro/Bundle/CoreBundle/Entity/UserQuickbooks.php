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
use JMS\Serializer\Annotation\SerializedName;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Validator\Constraints as Assert;

use Tavro\Bundle\CoreBundle\Model\Entity;
use Tavro\Bundle\CoreBundle\Model\EntityInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Bundle\CoreBundle\Doctrine\Repository\Entity\UserQuickbooksRepository")
 * @Table(name="tavro_user_quickbooks")
 * @ORM\HasLifecycleCallbacks
 *
 */
class UserQuickbooks extends Entity implements EntityInterface
{
    
    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     * @Groups({"tavro"})
     */
    protected $access_token;

    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     * @Groups({"tavro"})
     */
    protected $access_token_secret;

    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     * @Groups({"tavro"})
     */
    protected $consumer_key;

    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     * @Groups({"tavro"})
     */
    protected $consumer_secret;

    /**
     * @ORM\OneToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     * @Groups({"api", "tavro", "simple"})
     * @MaxDepth(1)
     */
    protected $user;

    /**
     * @param $string
     * @return string
     */
    protected function encrypt($string)
    {
        return $this::staticEncrypt($string);
    }

    /**
     * Decrypts the string with a 2 way hash.
     */
    protected function decrypt($string)
    {
        return openssl_decrypt($string, "AES-256-CBC", 'z8arRu38bAujXiCa7keqhEx2xAJKq9zz', 0, '5728582182961174');
    }

    public static function staticEncrypt($string)
    {
        return openssl_encrypt($string, "AES-256-CBC", 'z8arRu38bAujXiCa7keqhEx2xAJKq9zz', 0, '5728582182961174');
    }

    /**
     * Set accessToken
     *
     * @param string $accessToken
     *
     * @return UserQuickbooks
     */
    public function setAccessToken($accessToken)
    {
        $this->access_token = $this->encrypt($accessToken);

        return $this;
    }

    /**
     * Get accessToken
     *
     * @return string
     */
    public function getAccessToken()
    {
        return $this->decrypt($this->access_token);
    }

    /**
     * Set accessTokenSecret
     *
     * @param string $accessTokenSecret
     *
     * @return UserQuickbooks
     */
    public function setAccessTokenSecret($accessTokenSecret)
    {
        $this->access_token_secret = $this->encrypt($accessTokenSecret);

        return $this;
    }

    /**
     * Get accessTokenSecret
     *
     * @return string
     */
    public function getAccessTokenSecret()
    {
        return $this->decrypt($this->access_token_secret);
    }

    /**
     * Set consumerKey
     *
     * @param string $consumerKey
     *
     * @return UserQuickbooks
     */
    public function setConsumerKey($consumerKey)
    {
        $this->consumer_key = $this->encrypt($consumerKey);

        return $this;
    }

    /**
     * Get consumerKey
     *
     * @return string
     */
    public function getConsumerKey()
    {
        return $this->decrypt($this->consumer_key);
    }

    /**
     * Set consumerSecret
     *
     * @param string $consumerSecret
     *
     * @return UserQuickbooks
     */
    public function setConsumerSecret($consumerSecret)
    {
        $this->consumer_secret = $this->encrypt($consumerSecret);

        return $this;
    }

    /**
     * Get consumerSecret
     *
     * @return string
     */
    public function getConsumerSecret()
    {
        return $this->decrypt($this->consumer_secret);
    }

    /**
     * Set user
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     *
     * @return UserQuickbooks
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
     * Set body
     *
     * @param string $body
     *
     * @return UserQuickbooks
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

<?php

namespace App\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Rhumsaa\Uuid\Uuid;

use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\XmlRoot;
use JMS\Serializer\Annotation\XmlNamespace;
use JMS\Serializer\Annotation\SerializedName;
use Doctrine\ORM\Mapping\Table;

use Symfony\Component\Validator\Constraints as Assert;

use App\Model\EntityInterface\UserInterface;
use App\Model\Entity\Entity;
use App\Model\EntityInterface\EntityInterface;
use App\Model\EventInterface\ApiCreateEventInterface;
use App\Model\EventInterface\ApiDeleteEventInterface;
use App\Model\EventInterface\ApiUpdateEventInterface;

use App\Component\Validator\Constraints as ApiAssert;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Api\Repository\Entity\UserRepository")
 * @Table(name="Api_user")
 *
 * @XmlRoot("user")
 * @ExclusionPolicy("all")
 */
class User extends Entity implements ApiCreateEventInterface, ApiDeleteEventInterface, ApiUpdateEventInterface, UserInterface, \Serializable
{
    const CREATE_EVENT_CLASS    = 'Api\Event\User\UserSignupEvent';
    const DELETE_EVENT_CLASS    = 'Api\Event\User\UserDeleteEvent';
    const UPDATE_EVENT_CLASS    = 'Api\Event\User\UserUpdateEvent';

    /**
     * @ORM\Column(type="string", length=255, unique=TRUE, nullable=FALSE)
     * @Groups({"api", "detail", "simple", "typeahead"})
     * @Expose
     */
    protected $username;

    /**
     * @ApiAssert\PasswordComplexity
     * @ORM\Column(type="string", length=255, nullable=FALSE)
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=TRUE)
     */
    protected $password_token;

    /**
     * @ORM\Column(type="datetime", nullable=TRUE)
     */
    protected $password_token_expire;

    /**
     * @ORM\Column(type="string", length=255, nullable=FALSE)
     */
    protected $salt;

    /**
     * @ORM\Column(type="string", length=500, nullable=TRUE)
     * @Groups({"detail"})
     * @Expose
     */
    protected $signature;

    /**
     * @ORM\Column(type="datetime", nullable=TRUE)
     * @Groups({"detail"})
     * @MaxDepth(1)
     * @Expose
     */
    protected $last_online_date;

    /**
     * @ORM\Column(type="datetime", nullable=TRUE)
     * @Groups({"detail"})
     * @MaxDepth(1)
     * @Expose
     */
    protected $activation_date;

    /**
     * @ORM\Column(type="string", length=255, nullable=FALSE)
     * @Accessor(getter="getApiKey", setter="setApiKey")
     * @Groups({"detail"})
     * @MaxDepth(1)
     * @Expose
     */
    protected $api_key;

    /**
     * @ORM\Column(type="string", length=255, nullable=FALSE)
     * @Accessor(getter="getApiPassword", setter="setApiPassword")
     * @Groups({"detail"})
     * @MaxDepth(1)
     * @Expose
     */
    protected $api_password;

    /**
     * @ORM\Column(type="boolean", nullable=FALSE)
     * @MaxDepth(1)
     */
    protected $api_enabled;

    /**
     * @ORM\Column(type="string", length=255, nullable=FALSE)
     * @Accessor(getter="getGuid", setter="setGuid")
     */
    protected $guid;

    /**
     * @ORM\Column(type="string", length=255, nullable=TRUE)
     * @Groups({"detail"})
     * @MaxDepth(1)
     */
    protected $user_ip;

    /**
     * @ORM\Column(type="string", length=255, nullable=TRUE)
     * @Groups({"detail"})
     * @MaxDepth(1)
     */
    protected $user_agent;

    /**
     * @ORM\ManyToMany(targetEntity="Api\Entity\Role", inversedBy="users")
     * @ORM\JoinTable(
     *     name="Api_user_role",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=FALSE)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id", nullable=FALSE)}
     * )
     * @Type("array<string>")
     * @Accessor(getter="getSerializedRoles")
     * @Groups({"detail"})
     * @MaxDepth(3)
     */
    protected $roles;

    /**
     * @ORM\OneToMany(targetEntity="Api\Entity\NodeRead", mappedBy="user")
     */
    protected $nodes_read;

    /**
     * @ORM\ManyToOne(targetEntity="Api\Entity\Image")
     * @ORM\JoinColumn(name="avatar_image_id", referencedColumnName="id", nullable=TRUE)
     * @Groups({"api", "detail", "simple"})
     * @MaxDepth(1)
     * @Expose
     */
    protected $avatar;

    /**
     * @ORM\OneToOne(targetEntity="Api\Entity\Person", inversedBy="user")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "detail", "simple"})
     * @MaxDepth(3)
     * @Expose
     */
    public $person;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->create_date = new \DateTime();
        $this->update_date = new \DateTime();
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();

        $this->guid = Uuid::uuid4();
        $this->salt = md5($this->guid);

        $this->resetApiKey();
        $this->resetApiPassword();

        $this->api_enabled = FALSE;
        $this->status = self::STATUS_PENDING;

    }

    public function __toString()
    {
        return strval($this->username);
    }

    /**
     * @param $string
     * @return string
     */
    protected function encrypt($string)
    {
        return self::staticEncrypt($string, $this->getGuid());
    }

    /**
     * Updates the API Key
     */
    public function resetApiKey()
    {
        $guid = Uuid::uuid4();
        $this->setGuid($guid);
        $this->setApiKey(Uuid::uuid5(Uuid::NAMESPACE_DNS, $guid));
    }

    /**
     * Refreshes the API Secret
     */
    public function resetApiPassword()
    {
        $salt = md5($this->guid);
        $this->setSalt($salt);
        $password = bin2hex(openssl_random_pseudo_bytes(12));
        $this->setApiPassword($password);
    }

    /**
     * Returns TRUE if users have same username
     *
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     *
     * @return boolean
     */
    public function equals(\Symfony\Component\Security\Core\User\UserInterface $user)
    {
        if ($user->getUsername() == $this->getUsername()) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize() {
        /*
         * ! Don't serialize $roles field !
         */
        return \serialize(array(
            $this->id,
            $this->username,
            $this->salt,
            $this->password,
            $this->status
        ));

    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized) {
        list (
            $this->id,
            $this->username,
            $this->salt,
            $this->password,
            $this->status
            ) = \unserialize($serialized);

    }

    /**
     * Used for interface, currently unused
     */
    public function eraseCredentials() {
        return;

    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set and encode the password string.
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set password_token
     *
     * @param string $passwordToken
     * @return User
     */
    public function setPasswordToken($passwordToken)
    {
        $this->password_token = $passwordToken;

        return $this;
    }

    /**
     * Get password_token
     *
     * @return string 
     */
    public function getPasswordToken()
    {
        return $this->password_token;
    }

    /**
     * Set password_token_expire
     *
     * @param \DateTime $passwordTokenExpire
     * @return User
     */
    public function setPasswordTokenExpire($passwordTokenExpire)
    {
        $this->password_token_expire = $passwordTokenExpire;

        return $this;
    }

    /**
     * Get password_token_expire
     *
     * @return \DateTime 
     */
    public function getPasswordTokenExpire()
    {
        return $this->password_token_expire;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set signature
     *
     * @param string $signature
     * @return User
     */
    public function setSignature($signature)
    {
        $this->signature = $signature;

        return $this;
    }

    /**
     * Get signature
     *
     * @return string 
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * Set last_online_date
     *
     * @param \DateTime $lastOnlineDate
     * @return User
     */
    public function setLastOnlineDate($lastOnlineDate)
    {
        $this->last_online_date = $lastOnlineDate;

        return $this;
    }

    /**
     * Get last_online_date
     *
     * @return \DateTime 
     */
    public function getLastOnlineDate()
    {
        return $this->last_online_date;
    }

    /**
     * Set api_key
     *
     * @param string $apiKey
     * @return User
     */
    public function setApiKey($apiKey)
    {
        $this->api_key = $this->encrypt($apiKey);

        return $this;
    }

    /**
     * Get api_key
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->decrypt($this->api_key);
    }

    /**
     * Set apiEnabled
     *
     * @param boolean $apiEnabled
     *
     * @return User
     */
    public function setApiEnabled($apiEnabled)
    {
        $this->api_enabled = $apiEnabled;

        return $this;
    }

    /**
     * Get apiEnabled
     *
     * @return boolean
     */
    public function getApiEnabled()
    {
        return $this->api_enabled;
    }

    /**
     * Set api_password
     *
     * @param string $apiPassword
     * @return User
     */
    public function setApiPassword($apiPassword)
    {
        $this->api_password = $this->encrypt($apiPassword);

        return $this;
    }

    /**
     * Get api_password
     *
     * @return string
     */
    public function getApiPassword()
    {
        return $this->api_enabled ? $this->decrypt($this->api_password) : '****************';
    }

    /**
     * Set guid
     *
     * @param string $guid
     * @return User
     */
    public function setGuid($guid)
    {
        $this->guid = $guid;

        return $this;
    }

    /**
     * Get guid
     *
     * @return string 
     */
    public function getGuid()
    {
        return $this->guid;
    }

    /**
     * Set user_ip
     *
     * @param string $userIp
     * @return User
     */
    public function setUserIp($userIp)
    {
        $this->user_ip = $userIp;

        return $this;
    }

    /**
     * Get user_ip
     *
     * @return string 
     */
    public function getUserIp()
    {
        return $this->user_ip;
    }

    /**
     * Add node
     *
     * @param \App\Entity\Node $node
     * @return User
     */
    public function addNode(\Api\Entity\Node $node)
    {
        $this->nodes[] = $node;

        return $this;
    }

    /**
     * Remove node
     *
     * @param \App\Entity\Node $node
     */
    public function removeNode(\Api\Entity\Node $node)
    {
        $this->nodes->removeElement($node);
    }

    /**
     * Get node
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNodes()
    {
        return $this->nodes;
    }

    /**
     * Add role
     *
     * @param \App\Entity\Role $role
     * @return User
     */
    public function addRole(\Api\Entity\Role $role)
    {
        $this->roles[] = $role;

        return $this;
    }

    /**
     * Remove role
     *
     * @param \App\Entity\Role $role
     */
    public function removeRole(\Api\Entity\Role $role)
    {
        $this->roles->removeElement($role);
    }

    /**
     * Get roles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRoles() {
        if (!empty($this->roles)) {
            return $this->roles->toArray();
        }
        return $this->roles;
    }

    public function getRoleNames() {
        $roles = [];
        foreach($this->roles as $role) {
            $roles[] = $role->getRole();
        }
        return $roles;
    }

    /**
     * Check if the current Password Token has expired.
     * @return boolean
     */
    public function passwordTokenExpired() {

        if( $this->getPasswordTokenExpire() < new \DateTime(date('Y-m-d', time())) ) {
            return TRUE;
        }

        return FALSE;

    }

    /**
     * Set user_agent
     *
     * @param string $userAgent
     * @return User
     */
    public function setUserAgent($userAgent)
    {
        $this->user_agent = $userAgent;

        return $this;
    }

    /**
     * Get user_agent
     *
     * @return string 
     */
    public function getUserAgent()
    {
        return $this->user_agent;
    }

    /**
     * @return array
     */
    public function getSerializedRoles()
    {
        $roles = array();
        foreach($this->roles as $role) {
            $roles[] = $role->getRole();
        }
        return $roles;
    }

    /**
     * @VirtualProperty
     * @SerializedName("node_count")
     * @Groups({"detail"})
     *
     */
    public function nodeCount()
    {
        return $this->nodes->count();
    }

    /**
     * Set avatar
     *
     * @param \App\Entity\Image $avatar
     * @return User
     */
    public function setAvatar(\Api\Entity\Image $avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return \Api\Entity\Image
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return User
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
     * Set activationDate
     *
     * @param \DateTime $activationDate
     *
     * @return User
     */
    public function setActivationDate($activationDate)
    {
        $this->activation_date = $activationDate;

        return $this;
    }

    /**
     * Get activationDate
     *
     * @return \DateTime
     */
    public function getActivationDate()
    {
        return $this->activation_date;
    }
}

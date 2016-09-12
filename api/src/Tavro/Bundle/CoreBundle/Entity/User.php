<?php

namespace Tavro\Bundle\CoreBundle\Entity;

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

use Tavro\Bundle\CoreBundle\Model\UserInterface;
use Tavro\Bundle\CoreBundle\Model\Entity;
use Tavro\Bundle\CoreBundle\Model\EntityInterface;
use Tavro\Bundle\CoreBundle\Component\Validator\Constraints as TavroAssert;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Bundle\CoreBundle\Repository\UserRepository")
 * @Table(name="tavro_user")
 *
 * @XmlRoot("user")
 */
class User extends Entity implements UserInterface, \Serializable
{
    /**
     * @ORM\Column(type="string", length=255, unique=TRUE, nullable=FALSE)
     * @Groups({"api", "tavro", "simple", "typeahead"})
     */
    protected $username;

    /**
     * @TavroAssert\PasswordComplexity
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
     * @Groups({"tavro"})
     */
    protected $signature;

    /**
     * @ORM\Column(type="datetime", nullable=TRUE)
     * @Groups({"tavro"})
     * @MaxDepth(1)
     */
    protected $last_online_date;

    /**
     * @ORM\Column(type="string", length=255, nullable=FALSE)
     * @Groups({"tavro"})
     * @Accessor(getter="getApiKey", setter="setApiKey")
     * @MaxDepth(1)
     */
    protected $api_key;

    /**
     * @ORM\Column(type="string", length=255, nullable=FALSE)
     * @Groups({"tavro"})
     * @Accessor(getter="getApiPassword", setter="setApiPassword")
     * @MaxDepth(1)
     */
    protected $api_password;

    /**
     * @ORM\Column(type="boolean", nullable=FALSE)
     * @MaxDepth(1)
     */
    protected $api_enabled;

    /**
     * @ORM\Column(type="string", length=255, nullable=FALSE)
     * @Groups({"tavro"})
     * @Accessor(getter="getGuid", setter="setGuid")
     */
    protected $guid;

    /**
     * @ORM\Column(type="string", length=255, nullable=TRUE)
     * @Groups({"tavro"})
     * @MaxDepth(1)
     */
    protected $user_ip;

    /**
     * @ORM\Column(type="string", length=255, nullable=TRUE)
     * @Groups({"tavro"})
     * @MaxDepth(1)
     */
    protected $user_agent;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\Node", mappedBy="user")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    protected $nodes;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\Organization", mappedBy="owner")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    protected $organizations;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\Comment", mappedBy="user")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    protected $comments;

    /**
     * @ORM\ManyToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\Role", inversedBy="users")
     * @ORM\JoinTable(
     *     name="tavro_user_role",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=FALSE)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id", nullable=FALSE)}
     * )
     * @Type("array<string>")
     * @Accessor(getter="getSerializedRoles")
     * @Groups({"tavro"})
     * @MaxDepth(3)
     */
    protected $roles;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\NodeRead", mappedBy="user")
     */
    protected $nodes_read;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\AccountUser", mappedBy="user")
     */
    protected $account_users;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Image")
     * @ORM\JoinColumn(name="avatar_image_id", referencedColumnName="id", nullable=TRUE)
     * @Groups({"api", "tavro", "simple"})
     * @MaxDepth(1)
     */
    protected $avatar;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Person")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id", nullable=TRUE)
     * @Groups({"api", "tavro", "simple"})
     * @MaxDepth(1)
     */
    protected $person;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->create_date = new \DateTime();
        $this->update_date = new \DateTime();
        $this->nodes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->organizations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->account_users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->nodes_read = new \Doctrine\Common\Collections\ArrayCollection();

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
        return $this::staticEncrypt($string);
    }

    /**
     * Decrypts the string with a 2 way hash.
     */
    protected function decrypt($string)
    {
        return openssl_decrypt($string, "AES-256-CBC", 'QWSFUD5TeCVwy4TJ2obaY5ghrOA6jd1K', 0, '5728582182961174');
    }

    public static function staticEncrypt($string)
    {
        return openssl_encrypt($string, "AES-256-CBC", 'QWSFUD5TeCVwy4TJ2obaY5ghrOA6jd1K', 0, '5728582182961174');
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
            $this->email,
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
            $this->email,
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
     * @param \Tavro\Bundle\CoreBundle\Entity\Node $node
     * @return User
     */
    public function addNode(\Tavro\Bundle\CoreBundle\Entity\Node $node)
    {
        $this->nodes[] = $node;

        return $this;
    }

    /**
     * Remove node
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Node $node
     */
    public function removeNode(\Tavro\Bundle\CoreBundle\Entity\Node $node)
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
     * Add comment
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Comment $comment
     * @return User
     */
    public function addComment(\Tavro\Bundle\CoreBundle\Entity\Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Comment $comment
     */
    public function removeComment(\Tavro\Bundle\CoreBundle\Entity\Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComment() {
        if (!empty($this->comments)) {
            return $this->comments->toArray();
        }
        return $this->comments;

    }

    /**
     * Add role
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Role $role
     * @return User
     */
    public function addRole(\Tavro\Bundle\CoreBundle\Entity\Role $role)
    {
        $this->roles[] = $role;

        return $this;
    }

    /**
     * Remove role
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Role $role
     */
    public function removeRole(\Tavro\Bundle\CoreBundle\Entity\Role $role)
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
     * Add nodes_read
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\NodeRead $nodesRead
     * @return User
     */
    public function addNodesRead(\Tavro\Bundle\CoreBundle\Entity\NodeRead $nodesRead)
    {
        $this->nodes_read[] = $nodesRead;

        return $this;
    }

    /**
     * Remove nodes_read
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\NodeRead $nodesRead
     */
    public function removeNodesRead(\Tavro\Bundle\CoreBundle\Entity\NodeRead $nodesRead)
    {
        $this->nodes_read->removeElement($nodesRead);
    }

    /**
     * Get nodes_read
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNodesRead()
    {
        return $this->nodes_read;
    }

    /**
     * @param \Tavro\Bundle\CoreBundle\Entity\UserOrganization $user_organization
     *
     * @return $this
     */
    public function addUserOrganizations(\Tavro\Bundle\CoreBundle\Entity\UserOrganization $user_organization)
    {
        $this->user_organizations[] = $user_organization;

        return $this;
    }

    /**
     * Remove user_organization
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\UserOrganization $user_organization
     */
    public function removeUserOrganization(\Tavro\Bundle\CoreBundle\Entity\UserOrganization $user_organization)
    {
        $this->user_organizations->removeElement($user_organization);
    }

    /**
     * Get user_organizations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserOrganizations()
    {
        return $this->user_organizations;
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
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
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
     * @SerializedName("is_admin")
     * @Groups({"api", "tavro"})
     *
     */
    public function getIsAdmin()
    {
        foreach($this->roles as $role) {
            if($role->getRole() === 'ROLE_ADMIN') {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function isAdmin()
    {
        return $this->getIsAdmin();
    }

    public function isDeveloper()
    {
        return $this->getIsDeveloper();
    }

    /**
     * @VirtualProperty
     * @SerializedName("is_developer")
     * @Groups({"api", "tavro"})
     *
     */
    public function getIsDeveloper()
    {
        foreach($this->roles as $role) {
            if($role->getRole() === 'ROLE_DEVELOPER') {
                return TRUE;
            }
        }
        return FALSE;
    }

    /**
     * @VirtualProperty
     * @SerializedName("node_count")
     * @Groups({"tavro"})
     *
     */
    public function nodeCount()
    {
        return $this->nodes->count();
    }

    /**
     * Set enable_private_messages
     *
     * @param boolean $enableProtectedMessages
     * @return User
     */
    public function setEnableProtectedMessages($enableProtectedMessages)
    {
        $this->enable_private_messages = $enableProtectedMessages;

        return $this;
    }

    /**
     * Get enable_private_messages
     *
     * @return boolean 
     */
    public function getEnableProtectedMessages()
    {
        return $this->enable_private_messages;
    }

    /**
     * Set avatar
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Image $avatar
     * @return User
     */
    public function setAvatar(\Tavro\Bundle\CoreBundle\Entity\Image $avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\Image
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Add organization
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Organization $organization
     *
     * @return User
     */
    public function addOrganization(\Tavro\Bundle\CoreBundle\Entity\Organization $organization)
    {
        $this->organizations[] = $organization;

        return $this;
    }

    /**
     * Remove organization
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Organization $organization
     */
    public function removeOrganization(\Tavro\Bundle\CoreBundle\Entity\Organization $organization)
    {
        $this->organizations->removeElement($organization);
    }

    /**
     * Get organizations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrganizations()
    {
        return $this->organizations;
    }

    /**
     * Add userOrganization
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\UserOrganization $userOrganization
     *
     * @return User
     */
    public function addUserOrganization(\Tavro\Bundle\CoreBundle\Entity\UserOrganization $userOrganization)
    {
        $this->user_organizations[] = $userOrganization;

        return $this;
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
     * Set person
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Person $person
     *
     * @return User
     */
    public function setPerson(\Tavro\Bundle\CoreBundle\Entity\Person $person = null)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\Person
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Add accountUser
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\AccountUser $accountUser
     *
     * @return User
     */
    public function addAccountUser(\Tavro\Bundle\CoreBundle\Entity\AccountUser $accountUser)
    {
        $this->account_users[] = $accountUser;

        return $this;
    }

    /**
     * Remove accountUser
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\AccountUser $accountUser
     */
    public function removeAccountUser(\Tavro\Bundle\CoreBundle\Entity\AccountUser $accountUser)
    {
        $this->account_users->removeElement($accountUser);
    }

    /**
     * Get accountUsers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAccountUsers()
    {
        return $this->account_users;
    }
}

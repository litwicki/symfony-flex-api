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
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 * @Table(name="tavro_user")
 *
 * @XmlRoot("user")
 */
class User extends Entity implements UserInterface, \Serializable
{
    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     * @Groups({"api", "tavro", "simple", "typeahead"})
     */
    protected $username;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $password_token;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $password_token_expire;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $salt;

    /**
     * @ORM\Column(type="string", unique=true, length=255, nullable=false)
     * @Groups({"api", "tavro", "simple", "typeahead"})
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     * @Groups({"api", "tavro"})
     */
    protected $signature;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"api", "tavro", "simple"})
     * @MaxDepth(1)
     */
    protected $last_online_date;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Groups({"api", "tavro"})
     * @Accessor(getter="getApiKey", setter="setApiKey")
     * @MaxDepth(1)
     */
    protected $api_key;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Groups({"api", "tavro"})
     * @Accessor(getter="getApiPassword", setter="setApiPassword")
     * @MaxDepth(1)
     */
    protected $api_password;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     * @Groups({"api", "tavro"})
     * @MaxDepth(1)
     */
    protected $api_enabled;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Groups({"api", "tavro"})
     * @Accessor(getter="getGuid", setter="setGuid")
     */
    protected $guid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"api", "tavro"})
     * @MaxDepth(1)
     */
    protected $user_ip;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     * @Assert\Choice(
     *      choices = { "male", "female", "other" },
     *      message = "Choose a valid gender."
     * )
     * @Groups({"api", "tavro", "simple"})
     * @Accessor(getter="getGender", setter="setGuid")
     * @MaxDepth(1)
     */
    protected $gender;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"api", "tavro"})
     * @MaxDepth(1)
     */
    protected $user_agent;

    /**
     * @ORM\Column(type="date", length=255, nullable=true)
     * @Groups({"api", "tavro"})
     * @MaxDepth(1)
     */
    protected $birthday;

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
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id", nullable=false)}
     * )
     * @Type("array<string>")
     * @Accessor(getter="getSerializedRoles")
     * @Groups({"api", "tavro"})
     * @MaxDepth(3)
     */
    protected $roles;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\NodeRead", mappedBy="user")
     */
    protected $nodes_read;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\UserOrganization", mappedBy="user")
     */
    protected $user_organizations;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Image")
     * @ORM\JoinColumn(name="avatar_image_id", referencedColumnName="id", nullable=true)
     * @Groups({"api", "tavro", "simple"})
     * @MaxDepth(1)
     */
    protected $avatar;

    /**
     * @ORM\OneToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\UserQuickbooks")
     * @ORM\JoinColumn(name="user_quickbooks_id", referencedColumnName="id", nullable=true)
     * @Groups({"api", "tavro"})
     * @MaxDepth(1)
     */
    protected $user_quickbooks;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->create_date = new \DateTime();
        $this->update_date = new \DateTime();
        $this->nodes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->organizations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->user_organizations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->nodes_read = new \Doctrine\Common\Collections\ArrayCollection();

        $this->guid = Uuid::uuid4();
        $this->salt = md5($this->guid);

        $this->resetApiKey();
        $this->resetApiPassword();

        $this->api_enabled = false;

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
     * Set github_username
     *
     * @param string $github_username
     * @return User
     */
    public function setGithubUsername($github_username)
    {
        $this->github_username = $github_username;

        return $this;
    }

    /**
     * Get github_username
     *
     * @return string
     */
    public function getGithubUsername()
    {
        return $this->github_username;
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
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
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

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     * @return User
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime 
     */
    public function getBirthday()
    {
        return $this->birthday;
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
     * Add user_organization
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\UserOrganization $user_organization
     * @return UserOrganization
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
     * Set billing_id
     *
     * @param integer $billing_id
     * @return User
     */
    public function setBillingId($billing_id)
    {
        $this->billing_id = $billing_id;

        return $this;
    }

    /**
     * Get billing_id
     *
     * @return integer 
     */
    public function getBillingId()
    {
        return $this->billing_id;
    }

    /**
     * Check if the current Password Token has expired.
     * @return boolean
     */
    public function passwordTokenExpired() {

        if( $this->getPasswordTokenExpire() < new \DateTime(date('Y-m-d', time())) ) {
            return true;
        }

        return false;

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
     * Set gender
     *
     * @param string $gender
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return is_null($this->gender) ? '' : $this->gender;
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
        $roles = $this->getRoles();
        foreach($roles as $role) {
            if($role->getRole() === 'ROLE_ADMIN') {
                return true;
            }
        }
        return false;
    }

    /**
     * @VirtualProperty
     * @SerializedName("is_developer")
     * @Groups({"api", "tavro"})
     *
     */
    public function getIsDeveloper()
    {
        $roles = $this->getRoles();
        foreach($roles as $role) {
            if($role->getRole() === 'ROLE_DEVELOPER') {
                return true;
            }
        }
        return false;
    }

    /**
     * @VirtualProperty
     * @SerializedName("node_count")
     * @Groups({"api", "tavro"})
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
     * Set user_quickbooks
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\UserQuickbooks $user_quickbooks
     *
     * @return UserQuickbooks
     */
    public function setUser(\Tavro\Bundle\CoreBundle\Entity\UserQuickbooks $user_quickbooks)
    {
        $this->user_quickbooks = $user_quickbooks;

        return $this;
    }

    /**
     * Get user_quickbooks
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\UserQuickbooks
     */
    public function getUserQuickbooks()
    {
        return $this->user_quickbooks;
    }

    /**
     * Set userQuickbooks
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\UserQuickbooks $userQuickbooks
     *
     * @return User
     */
    public function setUserQuickbooks(\Tavro\Bundle\CoreBundle\Entity\UserQuickbooks $userQuickbooks)
    {
        $this->user_quickbooks = $userQuickbooks;

        return $this;
    }
}

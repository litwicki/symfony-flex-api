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
use App\Model\EventInterface\TavroCreateEventInterface;
use App\Model\EventInterface\TavroDeleteEventInterface;
use App\Model\EventInterface\TavroUpdateEventInterface;

use App\Component\Validator\Constraints as TavroAssert;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Repository\Entity\UserRepository")
 * @Table(name="tavro_user")
 *
 * @XmlRoot("user")
 * @ExclusionPolicy("all")
 */
class User extends Entity implements TavroCreateEventInterface, TavroDeleteEventInterface, TavroUpdateEventInterface, UserInterface, \Serializable
{
    const CREATE_EVENT_CLASS    = 'Tavro\Event\User\UserSignupEvent';
    const DELETE_EVENT_CLASS    = 'Tavro\Event\User\UserDeleteEvent';
    const UPDATE_EVENT_CLASS    = 'Tavro\Event\User\UserUpdateEvent';

    /**
     * @ORM\Column(type="string", length=255, unique=TRUE, nullable=FALSE)
     * @Groups({"api", "detail", "simple", "typeahead"})
     * @Expose
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
     * @ORM\OneToMany(targetEntity="Tavro\Entity\Expense", mappedBy="user")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    protected $expenses;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Entity\Forecast", mappedBy="user")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    protected $forecasts;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Entity\Revenue", mappedBy="user")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    protected $revenues;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Entity\Node", mappedBy="user")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    protected $nodes;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Entity\Syndicate", mappedBy="user")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    protected $syndicates;

    /**
     * @ORM\ManyToMany(targetEntity="Tavro\Entity\Syndicate", mappedBy="investors")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    protected $syndicate_memberships;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Entity\Account", mappedBy="user")
     * @ORM\OrderBy({"body" = "ASC"})
     */
    protected $accounts;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Entity\AccountGroup", mappedBy="user")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    protected $account_groups;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Entity\AccountGroupUser", mappedBy="user")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    protected $account_group_users;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Entity\Comment", mappedBy="user")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    protected $comments;

    /**
     * @ORM\ManyToMany(targetEntity="Tavro\Entity\Role", inversedBy="users")
     * @ORM\JoinTable(
     *     name="tavro_user_role",
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
     * @ORM\OneToMany(targetEntity="Tavro\Entity\NodeRead", mappedBy="user")
     */
    protected $nodes_read;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Entity\AccountUser", mappedBy="user")
     */
    protected $account_users;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\Image")
     * @ORM\JoinColumn(name="avatar_image_id", referencedColumnName="id", nullable=TRUE)
     * @Groups({"api", "detail", "simple"})
     * @MaxDepth(1)
     * @Expose
     */
    protected $avatar;

    /**
     * @ORM\OneToOne(targetEntity="Tavro\Entity\Person", inversedBy="user")
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
        $this->nodes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->account_users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->account_groups = new \Doctrine\Common\Collections\ArrayCollection();
        $this->accounts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->revenues = new \Doctrine\Common\Collections\ArrayCollection();
        $this->expenses = new \Doctrine\Common\Collections\ArrayCollection();
        $this->forecasts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->syndicates = new \Doctrine\Common\Collections\ArrayCollection();
        $this->syndicate_memberships = new \Doctrine\Common\Collections\ArrayCollection();
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
    public function addNode(\Tavro\Entity\Node $node)
    {
        $this->nodes[] = $node;

        return $this;
    }

    /**
     * Remove node
     *
     * @param \App\Entity\Node $node
     */
    public function removeNode(\Tavro\Entity\Node $node)
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
     * @param \App\Entity\Comment $comment
     * @return User
     */
    public function addComment(\Tavro\Entity\Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \App\Entity\Comment $comment
     */
    public function removeComment(\Tavro\Entity\Comment $comment)
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
     * @param \App\Entity\Role $role
     * @return User
     */
    public function addRole(\Tavro\Entity\Role $role)
    {
        $this->roles[] = $role;

        return $this;
    }

    /**
     * Remove role
     *
     * @param \App\Entity\Role $role
     */
    public function removeRole(\Tavro\Entity\Role $role)
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
     * @param \App\Entity\NodeRead $nodesRead
     * @return User
     */
    public function addNodesRead(\Tavro\Entity\NodeRead $nodesRead)
    {
        $this->nodes_read[] = $nodesRead;

        return $this;
    }

    /**
     * Remove nodes_read
     *
     * @param \App\Entity\NodeRead $nodesRead
     */
    public function removeNodesRead(\Tavro\Entity\NodeRead $nodesRead)
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
     * @Groups({"api", "detail"})
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
     * @SerializedName("user_accounts")
     * @Groups({"api", "simple", "detail"})
     * @MaxDepth(2)
     */
    public function getUserAccounts()
    {
        $items = new \Doctrine\Common\Collections\ArrayCollection();

        foreach($this->account_users as $entity) {
            $items->add($entity->getAccount());
        }

        foreach($this->accounts as $entity) {
            $items->add($entity);
        }

        return $items;
    }

    /**
     * @VirtualProperty
     * @SerializedName("is_developer")
     * @Groups({"api", "detail"})
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
     * @Groups({"detail"})
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
     * @param \App\Entity\Image $avatar
     * @return User
     */
    public function setAvatar(\Tavro\Entity\Image $avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return \Tavro\Entity\Image
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
     * Set person
     *
     * @param \App\Entity\Person $person
     *
     * @return User
     */
    public function setPerson(\Tavro\Entity\Person $person = null)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person
     *
     * @return \Tavro\Entity\Person
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Add accountUser
     *
     * @param \App\Entity\AccountUser $accountUser
     *
     * @return User
     */
    public function addAccountUser(\Tavro\Entity\AccountUser $accountUser)
    {
        $this->account_users[] = $accountUser;

        return $this;
    }

    /**
     * Remove accountUser
     *
     * @param \App\Entity\AccountUser $accountUser
     */
    public function removeAccountUser(\Tavro\Entity\AccountUser $accountUser)
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

    /**
     * Add expense
     *
     * @param \App\Entity\Expense $expense
     *
     * @return User
     */
    public function addExpense(\Tavro\Entity\Expense $expense)
    {
        $this->expenses[] = $expense;

        return $this;
    }

    /**
     * Remove expense
     *
     * @param \App\Entity\Expense $expense
     */
    public function removeExpense(\Tavro\Entity\Expense $expense)
    {
        $this->expenses->removeElement($expense);
    }

    /**
     * Get expenses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExpenses()
    {
        return $this->expenses;
    }

    /**
     * Add revenue
     *
     * @param \App\Entity\Revenue $revenue
     *
     * @return User
     */
    public function addRevenue(\Tavro\Entity\Revenue $revenue)
    {
        $this->revenues[] = $revenue;

        return $this;
    }

    /**
     * Remove revenue
     *
     * @param \App\Entity\Revenue $revenue
     */
    public function removeRevenue(\Tavro\Entity\Revenue $revenue)
    {
        $this->revenues->removeElement($revenue);
    }

    /**
     * Get revenues
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRevenues()
    {
        return $this->revenues;
    }

    /**
     * Add account
     *
     * @param \App\Entity\Account $account
     *
     * @return User
     */
    public function addAccount(\Tavro\Entity\Account $account)
    {
        $this->accounts[] = $account;

        return $this;
    }

    /**
     * Remove account
     *
     * @param \App\Entity\Account $account
     */
    public function removeAccount(\Tavro\Entity\Account $account)
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

    /**
     * Add accountGroup
     *
     * @param \App\Entity\AccountGroup $accountGroup
     *
     * @return User
     */
    public function addAccountGroup(\Tavro\Entity\AccountGroup $accountGroup)
    {
        $this->account_groups[] = $accountGroup;

        return $this;
    }

    /**
     * Remove accountGroup
     *
     * @param \App\Entity\AccountGroup $accountGroup
     */
    public function removeAccountGroup(\Tavro\Entity\AccountGroup $accountGroup)
    {
        $this->account_groups->removeElement($accountGroup);
    }

    /**
     * Get accountGroups
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAccountGroups()
    {
        return $this->account_groups;
    }

    /**
     * Add accountGroupUser
     *
     * @param \App\Entity\AccountGroupUser $accountGroupUser
     *
     * @return User
     */
    public function addAccountGroupUser(\Tavro\Entity\AccountGroupUser $accountGroupUser)
    {
        $this->account_group_users[] = $accountGroupUser;

        return $this;
    }

    /**
     * Remove accountGroupUser
     *
     * @param \App\Entity\AccountGroupUser $accountGroupUser
     */
    public function removeAccountGroupUser(\Tavro\Entity\AccountGroupUser $accountGroupUser)
    {
        $this->account_group_users->removeElement($accountGroupUser);
    }

    /**
     * Get accountGroupUsers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAccountGroupUsers()
    {
        return $this->account_group_users;
    }

    /**
     * Add syndicate
     *
     * @param \App\Entity\Syndicate $syndicate
     *
     * @return User
     */
    public function addSyndicate(\Tavro\Entity\Syndicate $syndicate)
    {
        $this->syndicates[] = $syndicate;

        return $this;
    }

    /**
     * Remove syndicate
     *
     * @param \App\Entity\Syndicate $syndicate
     */
    public function removeSyndicate(\Tavro\Entity\Syndicate $syndicate)
    {
        $this->syndicates->removeElement($syndicate);
    }

    /**
     * Get syndicates
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSyndicates()
    {
        return $this->syndicates;
    }

    /**
     * Add forecast
     *
     * @param \App\Entity\Forecast $forecast
     *
     * @return User
     */
    public function addForecast(\Tavro\Entity\Forecast $forecast)
    {
        $this->forecasts[] = $forecast;

        return $this;
    }

    /**
     * Remove forecast
     *
     * @param \App\Entity\Forecast $forecast
     */
    public function removeForecast(\Tavro\Entity\Forecast $forecast)
    {
        $this->forecasts->removeElement($forecast);
    }

    /**
     * Get forecasts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getForecasts()
    {
        return $this->forecasts;
    }

    /**
     * Add syndicateMembership
     *
     * @param \App\Entity\Syndicate $syndicateMembership
     *
     * @return User
     */
    public function addSyndicateMembership(\Tavro\Entity\Syndicate $syndicateMembership)
    {
        $this->syndicate_memberships[] = $syndicateMembership;

        return $this;
    }

    /**
     * Remove syndicateMembership
     *
     * @param \App\Entity\Syndicate $syndicateMembership
     */
    public function removeSyndicateMembership(\Tavro\Entity\Syndicate $syndicateMembership)
    {
        $this->syndicate_memberships->removeElement($syndicateMembership);
    }

    /**
     * Get syndicateMemberships
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSyndicateMemberships()
    {
        return $this->syndicate_memberships;
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

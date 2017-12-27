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
use Doctrine\ORM\Mapping\Table;
use JMS\Serializer\Annotation\MaxDepth;

use Tavro\Bundle\CoreBundle\Model\Entity\Entity;
use Tavro\Bundle\CoreBundle\Model\EntityInterface\EntityInterface;
use Tavro\Bundle\CoreBundle\Model\EventInterface\TavroCreateEventInterface;
use Tavro\Bundle\CoreBundle\Model\EventInterface\TavroDeleteEventInterface;
use Tavro\Bundle\CoreBundle\Model\EventInterface\TavroUpdateEventInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Bundle\CoreBundle\Repository\Entity\AccountRepository")
 * @ORM\HasLifecycleCallbacks
 * @Table(name="tavro_account")
 * @ExclusionPolicy("all")
 */
class Account extends Entity implements EntityInterface, TavroCreateEventInterface, TavroDeleteEventInterface, TavroUpdateEventInterface
{

    const CREATE_EVENT_CLASS    = 'Tavro\Bundle\CoreBundle\Event\Account\AccountCreateEvent';
    const DELETE_EVENT_CLASS    = 'Tavro\Bundle\CoreBundle\Event\Account\AccountDeleteEvent';
    const UPDATE_EVENT_CLASS    = 'Tavro\Bundle\CoreBundle\Event\Account\AccountUpdateEvent';
    const TRIAL_LENGTH_DAYS     = 30;

    /**
     * @ORM\Column(type="string", length=255, nullable=FALSE)
     * @Accessor(getter="getGuid", setter="setGuid")
     */
    protected $guid;

    /**
     * @ORM\Column(type="integer", nullable=TRUE)
     * @Groups({"api", "detail"})
     * @Expose
     */
    protected $customer_id;

    /**
     * @ORM\Column(type="string", length=500, nullable=TRUE)
     * @Groups({"api", "detail"})
     * @Expose
     */
    protected $structure;

    /**
     * @ORM\Column(type="integer", nullable=TRUE)
     * @Groups({"api", "detail"})
     * @Expose
     */
    protected $business_id;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\User", inversedBy="accounts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "detail"})
     * @MaxDepth(1)
     * @Expose
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\AccountType", inversedBy="accounts")
     * @ORM\JoinColumn(name="account_type_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "detail"})
     * @MaxDepth(1)
     * @Expose
     */
    protected $type;

    /**
     * @Type("datetime")
     * @ORM\Column(type="datetime", length=1000, nullable=TRUE)
     * @Groups({"api", "detail"})
     */
    protected $trial_start_date;

    /**
     * @Type("datetime")
     * @ORM\Column(type="datetime", length=1000, nullable=TRUE)
     * @Groups({"api", "detail"})
     */
    protected $trial_end_date;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\AccountUser", mappedBy="account", cascade={"remove"})
     */
    protected $account_users;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\AccountGroup", mappedBy="account", cascade={"remove"})
     */
    protected $account_groups;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\Organization", mappedBy="account", cascade={"remove"})
     */
    protected $organizations;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\StaffPerson", mappedBy="account", cascade={"remove"})
     */
    protected $staff_persons;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\FundingRound", mappedBy="account", cascade={"remove"})
     */
    protected $funding_rounds;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\Document", mappedBy="account", cascade={"remove"})
     */
    protected $documents;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\Image", mappedBy="account", cascade={"remove"})
     */
    protected $images;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\Node", mappedBy="account", cascade={"remove"})
     */
    protected $nodes;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\Forecast", mappedBy="account", cascade={"remove"})
     */
    protected $forecasts;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\Service", mappedBy="account", cascade={"remove"})
     */
    protected $services;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\Product", mappedBy="account", cascade={"remove"})
     */
    protected $products;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\Expense", mappedBy="account", cascade={"remove"})
     */
    protected $expenses;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\Revenue", mappedBy="account", cascade={"remove"})
     */
    protected $revenues;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\Variable", mappedBy="account", cascade={"remove"})
     */
    protected $variables;

    /**
     * @ORM\Column(type="string", unique=TRUE, length=500, nullable=FALSE)
     * @Groups({"api", "detail", "simple"})
     * @Expose
     */
    protected $name;

    /**
     * @ORM\Column(type="string", unique=TRUE, length=500, nullable=FALSE)
     * @Groups({"api", "detail", "simple"})
     * @Expose
     */
    protected $name_clean;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\ServiceCategory", mappedBy="account", cascade={"remove"})
     * @Groups({"detail"})
     * @MaxDepth(3)
     */
    protected $service_categories;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\ExpenseCategory", mappedBy="account", cascade={"remove"})
     * @Groups({"detail"})
     * @MaxDepth(3)
     */
    protected $expense_categories;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\ProductCategory", mappedBy="account", cascade={"remove"})
     * @Groups({"detail"})
     * @MaxDepth(3)
     */
    protected $product_categories;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\RevenueCategory", mappedBy="account", cascade={"remove"})
     * @Groups({"detail"})
     * @MaxDepth(3)
     */
    protected $revenue_categories;

    /**
     * @ORM\OneToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\AccountChargify", mappedBy="account", cascade={"remove"})
     */
    protected $account_chargify;

    /**
     * @ORM\OneToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\AccountFreshbooks", mappedBy="account", cascade={"remove"})
     */
    protected $account_freshbooks;

    /**
     * @ORM\OneToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\AccountHarvest", mappedBy="account", cascade={"remove"})
     */
    protected $account_harvest;

    /**
     * @ORM\OneToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\AccountHubspot", mappedBy="account", cascade={"remove"})
     */
    protected $account_hubspot;

    /**
     * @ORM\OneToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\AccountQbo", mappedBy="account", cascade={"remove"})
     */
    protected $account_qbo;

    /**
     * @ORM\OneToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\AccountRecurly", mappedBy="account", cascade={"remove"})
     */
    protected $account_recurly;

    /**
     * @ORM\OneToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\AccountStripe", mappedBy="account", cascade={"remove"})
     */
    protected $account_stripe;

    /**
     * @ORM\Column(type="boolean", nullable=FALSE)
     * @Groups({"api", "detail"})
     * @Expose
     */
    protected $is_receiving_marketing_emails;

    /**
     * @ORM\Column(type="boolean", nullable=FALSE)
     * @Groups({"api", "detail"})
     * @Expose
     */
    protected $is_receiving_security_emails;

    /**
     * @ORM\Column(type="boolean", nullable=FALSE)
     * @Groups({"api", "detail"})
     * @Expose
     */
    protected $is_receiving_marketplace_emails;

    /**
     * @ORM\Column(type="boolean", nullable=FALSE)
     * @Groups({"api", "detail"})
     * @Expose
     */
    protected $is_receiving_activity_emails;

    /**
     * @ORM\Column(type="boolean", nullable=FALSE)
     * @Groups({"api", "detail"})
     * @Expose
     */
    protected $is_receiving_general_emails;

    /**
     * Automatically set the create_date and last_update_date on persist.
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->setNameClean($this->createCleanName($this->getName()));

        if(is_null($this->create_date)) {
            $this->create_date = new \DateTime();
        }

        $this->update_date = new \DateTime();

        /**
         * If we've set a Trial Start Date, but NOT a Trial End Date,
         * Default to TRIAL_LENGTH_DAYS days. Otherwise, allow this to be set
         * on an account-by-account basis, so we can be flexible.
         */
        if(!is_null($this->trial_start_date) && is_null($this->trial_end_date)) {
            $this->trial_end_date = new \DateTime($this->getTrialStartDate()->format('Y-m-d'));
            $this->trial_end_date->add(new \DateInterval(sprintf('P%sD', TRIAL_LENGTH_DAYS)));
        }

    }

    public function __construct()
    {
        parent::__construct();
        $this->organizations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->account_users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
        $this->funding_rounds = new \Doctrine\Common\Collections\ArrayCollection();
        $this->services = new \Doctrine\Common\Collections\ArrayCollection();
        $this->revenues = new \Doctrine\Common\Collections\ArrayCollection();
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
        $this->expenses = new \Doctrine\Common\Collections\ArrayCollection();
        $this->service_categories = new \Doctrine\Common\Collections\ArrayCollection();
        $this->expense_categories = new \Doctrine\Common\Collections\ArrayCollection();
        $this->product_categories = new \Doctrine\Common\Collections\ArrayCollection();
        $this->revenue_categories = new \Doctrine\Common\Collections\ArrayCollection();
        $this->guid = Uuid::uuid4();
        $this->is_receiving_general_emails = true;
        $this->is_receiving_activity_emails = true;
        $this->is_receiving_marketplace_emails = true;
        $this->is_receiving_security_emails = true;
        $this->is_receiving_marketing_emails = true;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return Account
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
     * Add accountUser
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\AccountUser $accountUser
     *
     * @return Account
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

    public static function createCleanName($name)
    {

        $clean = preg_replace('/[^A-Za-z0-9-_\s]/', '', $name);
        $clean = str_replace(' ', '-', $clean);
        $clean = str_replace('_', '-', $clean);
        $clean = strtolower($clean);

        return $clean;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Account
     */
    public function setName($name)
    {
        $this->name = $name;

        $clean = $this->createCleanName($name);

        $this->setNameClean($clean);

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set nameClean
     *
     * @param string $nameClean
     *
     * @return Account
     */
    public function setNameClean($nameClean)
    {
        $this->name_clean = $nameClean;

        return $this;
    }

    /**
     * Get nameClean
     *
     * @return string
     */
    public function getNameClean()
    {
        return $this->name_clean;
    }

    /**
     * Set user
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     *
     * @return Account
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
     * Get All the User entities associated via AccountUsers.
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getUsers()
    {
        $users = new \Doctrine\Common\Collections\ArrayCollection();
        foreach($this->account_users as $entity) {
            $users->add($entity->getUser());
        }
        return $users;

    }

    /**
     * Add organization
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Organization $organization
     *
     * @return Account
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
     * Add serviceCategory
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\ServiceCategory $serviceCategory
     *
     * @return Account
     */
    public function addServiceCategory(\Tavro\Bundle\CoreBundle\Entity\ServiceCategory $serviceCategory)
    {
        $this->service_categories[] = $serviceCategory;

        return $this;
    }

    /**
     * Remove serviceCategory
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\ServiceCategory $serviceCategory
     */
    public function removeServiceCategory(\Tavro\Bundle\CoreBundle\Entity\ServiceCategory $serviceCategory)
    {
        $this->service_categories->removeElement($serviceCategory);
    }

    /**
     * Get serviceCategories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServiceCategories()
    {
        return $this->service_categories;
    }

    /**
     * Add expenseCategory
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\ExpenseCategory $expenseCategory
     *
     * @return Account
     */
    public function addExpenseCategory(\Tavro\Bundle\CoreBundle\Entity\ExpenseCategory $expenseCategory)
    {
        $this->expense_categories[] = $expenseCategory;

        return $this;
    }

    /**
     * Remove expenseCategory
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\ExpenseCategory $expenseCategory
     */
    public function removeExpenseCategory(\Tavro\Bundle\CoreBundle\Entity\ExpenseCategory $expenseCategory)
    {
        $this->expense_categories->removeElement($expenseCategory);
    }

    /**
     * Get expenseCategories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExpenseCategories()
    {
        return $this->expense_categories;
    }

    /**
     * Add productCategory
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\ProductCategory $productCategory
     *
     * @return Account
     */
    public function addProductCategory(\Tavro\Bundle\CoreBundle\Entity\ProductCategory $productCategory)
    {
        $this->product_categories[] = $productCategory;

        return $this;
    }

    /**
     * Remove productCategory
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\ProductCategory $productCategory
     */
    public function removeProductCategory(\Tavro\Bundle\CoreBundle\Entity\ProductCategory $productCategory)
    {
        $this->product_categories->removeElement($productCategory);
    }

    /**
     * Get productCategories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProductCategories()
    {
        return $this->product_categories;
    }

    /**
     * Add revenueCategory
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\RevenueCategory $revenueCategory
     *
     * @return Account
     */
    public function addRevenueCategory(\Tavro\Bundle\CoreBundle\Entity\RevenueCategory $revenueCategory)
    {
        $this->revenue_categories[] = $revenueCategory;

        return $this;
    }

    /**
     * Remove revenueCategory
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\RevenueCategory $revenueCategory
     */
    public function removeRevenueCategory(\Tavro\Bundle\CoreBundle\Entity\RevenueCategory $revenueCategory)
    {
        $this->revenue_categories->removeElement($revenueCategory);
    }

    /**
     * Get revenueCategories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRevenueCategories()
    {
        return $this->revenue_categories;
    }

    /**
     * Add service
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Service $service
     *
     * @return Account
     */
    public function addService(\Tavro\Bundle\CoreBundle\Entity\Service $service)
    {
        $this->services[] = $service;

        return $this;
    }

    /**
     * Remove service
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Service $service
     */
    public function removeService(\Tavro\Bundle\CoreBundle\Entity\Service $service)
    {
        $this->services->removeElement($service);
    }

    /**
     * Get services
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * Add revenue
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Revenue $revenue
     *
     * @return Account
     */
    public function addRevenue(\Tavro\Bundle\CoreBundle\Entity\Revenue $revenue)
    {
        $this->revenues[] = $revenue;

        return $this;
    }

    /**
     * Remove revenue
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Revenue $revenue
     */
    public function removeRevenue(\Tavro\Bundle\CoreBundle\Entity\Revenue $revenue)
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
     * Add product
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Product $product
     *
     * @return Account
     */
    public function addProduct(\Tavro\Bundle\CoreBundle\Entity\Product $product)
    {
        $this->products[] = $product;

        return $this;
    }

    /**
     * Remove product
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Product $product
     */
    public function removeProduct(\Tavro\Bundle\CoreBundle\Entity\Product $product)
    {
        $this->products->removeElement($product);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Add expense
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Expense $expense
     *
     * @return Account
     */
    public function addExpense(\Tavro\Bundle\CoreBundle\Entity\Expense $expense)
    {
        $this->expenses[] = $expense;

        return $this;
    }

    /**
     * Remove expense
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Expense $expense
     */
    public function removeExpense(\Tavro\Bundle\CoreBundle\Entity\Expense $expense)
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
     * Add accountGroup
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\AccountGroup $accountGroup
     *
     * @return Account
     */
    public function addAccountGroup(\Tavro\Bundle\CoreBundle\Entity\AccountGroup $accountGroup)
    {
        $this->account_groups[] = $accountGroup;

        return $this;
    }

    /**
     * Remove accountGroup
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\AccountGroup $accountGroup
     */
    public function removeAccountGroup(\Tavro\Bundle\CoreBundle\Entity\AccountGroup $accountGroup)
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
     * Add fundingRound
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\FundingRound $fundingRound
     *
     * @return Account
     */
    public function addFundingRound(\Tavro\Bundle\CoreBundle\Entity\FundingRound $fundingRound)
    {
        $this->funding_rounds[] = $fundingRound;

        return $this;
    }

    /**
     * Remove fundingRound
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\FundingRound $fundingRound
     */
    public function removeFundingRound(\Tavro\Bundle\CoreBundle\Entity\FundingRound $fundingRound)
    {
        $this->funding_rounds->removeElement($fundingRound);
    }

    /**
     * Get fundingRounds
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFundingRounds()
    {
        return $this->funding_rounds;
    }

    /**
     * Get All Shareholders for this Account across all Funding Rounds.
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getShareholders()
    {
        $items = new \Doctrine\Common\Collections\ArrayCollection();
        foreach($this->funding_rounds as $funding_round) {
            foreach($funding_round->getFundingRoundShareholders() as $entity) {
                $items->add($entity->getShareholder());
            }
        }
        return $items;
    }

    /**
     * Add document
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Document $document
     *
     * @return Account
     */
    public function addDocument(\Tavro\Bundle\CoreBundle\Entity\Document $document)
    {
        $this->documents[] = $document;

        return $this;
    }

    /**
     * Remove document
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Document $document
     */
    public function removeDocument(\Tavro\Bundle\CoreBundle\Entity\Document $document)
    {
        $this->documents->removeElement($document);
    }

    /**
     * Get documents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDocuments()
    {
        return $this->documents;
    }

    /**
     * Add image
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Image $image
     *
     * @return Account
     */
    public function addImage(\Tavro\Bundle\CoreBundle\Entity\Image $image)
    {
        $this->images[] = $image;

        return $this;
    }

    /**
     * Remove image
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Image $image
     */
    public function removeImage(\Tavro\Bundle\CoreBundle\Entity\Image $image)
    {
        $this->images->removeElement($image);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Add node
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Node $node
     *
     * @return Account
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
     * Get nodes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNodes()
    {
        return $this->nodes;
    }

    /**
     * Add contact
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Contact $contact
     *
     * @return Account
     */
    public function addContact(\Tavro\Bundle\CoreBundle\Entity\Contact $contact)
    {
        $this->contacts[] = $contact;

        return $this;
    }

    /**
     * Remove contact
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Contact $contact
     */
    public function removeContact(\Tavro\Bundle\CoreBundle\Entity\Contact $contact)
    {
        $this->contacts->removeElement($contact);
    }

    /**
     * Get contacts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    public function fundingTotal()
    {
    	$total = 0;

    	foreach($this->funding_rounds as $fr) {
    		$round = $fr->getSharePrice() * $fr->getTotalShares();
		    $total += $round;
	    }

	    return $total;
    }

    /**
     * Add variable
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Variable $variable
     *
     * @return Account
     */
    public function addVariable(\Tavro\Bundle\CoreBundle\Entity\Variable $variable)
    {
        $this->variables[] = $variable;

        return $this;
    }

    /**
     * Remove variable
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Variable $variable
     */
    public function removeVariable(\Tavro\Bundle\CoreBundle\Entity\Variable $variable)
    {
        $this->variables->removeElement($variable);
    }

    /**
     * Get variables
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * Add forecast
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Forecast $forecast
     *
     * @return Account
     */
    public function addForecast(\Tavro\Bundle\CoreBundle\Entity\Forecast $forecast)
    {
        $this->forecasts[] = $forecast;

        return $this;
    }

    /**
     * Remove forecast
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Forecast $forecast
     */
    public function removeForecast(\Tavro\Bundle\CoreBundle\Entity\Forecast $forecast)
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
     * Set accountChargify
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\AccountChargify $accountChargify
     *
     * @return Account
     */
    public function setAccountChargify(\Tavro\Bundle\CoreBundle\Entity\AccountChargify $accountChargify = null)
    {
        $this->account_chargify = $accountChargify;

        return $this;
    }

    /**
     * Get accountChargify
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\AccountChargify
     */
    public function getAccountChargify()
    {
        return $this->account_chargify;
    }

    /**
     * Set accountFreshbooks
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\AccountChargify $accountFreshbooks
     *
     * @return Account
     */
    public function setAccountFreshbooks(\Tavro\Bundle\CoreBundle\Entity\AccountChargify $accountFreshbooks = null)
    {
        $this->account_freshbooks = $accountFreshbooks;

        return $this;
    }

    /**
     * Get accountFreshbooks
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\AccountChargify
     */
    public function getAccountFreshbooks()
    {
        return $this->account_freshbooks;
    }

    /**
     * Set accountHarvest
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\AccountChargify $accountHarvest
     *
     * @return Account
     */
    public function setAccountHarvest(\Tavro\Bundle\CoreBundle\Entity\AccountChargify $accountHarvest = null)
    {
        $this->account_harvest = $accountHarvest;

        return $this;
    }

    /**
     * Get accountHarvest
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\AccountChargify
     */
    public function getAccountHarvest()
    {
        return $this->account_harvest;
    }

    /**
     * Set accountHubspot
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\AccountChargify $accountHubspot
     *
     * @return Account
     */
    public function setAccountHubspot(\Tavro\Bundle\CoreBundle\Entity\AccountChargify $accountHubspot = null)
    {
        $this->account_hubspot = $accountHubspot;

        return $this;
    }

    /**
     * Get accountHubspot
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\AccountChargify
     */
    public function getAccountHubspot()
    {
        return $this->account_hubspot;
    }

    /**
     * Set accountQbo
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\AccountChargify $accountQbo
     *
     * @return Account
     */
    public function setAccountQbo(\Tavro\Bundle\CoreBundle\Entity\AccountChargify $accountQbo = null)
    {
        $this->account_qbo = $accountQbo;

        return $this;
    }

    /**
     * Get accountQbo
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\AccountChargify
     */
    public function getAccountQbo()
    {
        return $this->account_qbo;
    }

    /**
     * Set accountRecurly
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\AccountChargify $accountRecurly
     *
     * @return Account
     */
    public function setAccountRecurly(\Tavro\Bundle\CoreBundle\Entity\AccountChargify $accountRecurly = null)
    {
        $this->account_recurly = $accountRecurly;

        return $this;
    }

    /**
     * Get accountRecurly
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\AccountChargify
     */
    public function getAccountRecurly()
    {
        return $this->account_recurly;
    }

    /**
     * Set accountStripe
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\AccountChargify $accountStripe
     *
     * @return Account
     */
    public function setAccountStripe(\Tavro\Bundle\CoreBundle\Entity\AccountChargify $accountStripe = null)
    {
        $this->account_stripe = $accountStripe;

        return $this;
    }

    /**
     * Get accountStripe
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\AccountChargify
     */
    public function getAccountStripe()
    {
        return $this->account_stripe;
    }

    /**
     * Set guid
     *
     * @return Account
     */
    public function setGuid()
    {
        $this->guid = Uuid::uuid4();

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
     * Add staffPerson
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\StaffPerson $staffPerson
     *
     * @return Account
     */
    public function addStaffPerson(\Tavro\Bundle\CoreBundle\Entity\StaffPerson $staffPerson)
    {
        $this->staff_persons[] = $staffPerson;

        return $this;
    }

    /**
     * Remove staffPerson
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\StaffPerson $staffPerson
     */
    public function removeStaffPerson(\Tavro\Bundle\CoreBundle\Entity\StaffPerson $staffPerson)
    {
        $this->staff_persons->removeElement($staffPerson);
    }

    /**
     * Get staffPersons
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStaffPersons()
    {
        return $this->staff_persons;
    }


    /**
     * Set trialStartDate
     *
     * @param \DateTime $trialStartDate
     *
     * @return Account
     */
    public function setTrialStartDate($trialStartDate)
    {
        $this->trial_start_date = $trialStartDate;

        return $this;
    }

    /**
     * Get trialStartDate
     *
     * @return \DateTime
     */
    public function getTrialStartDate()
    {
        return $this->trial_start_date;
    }

    /**
     * Set trialEndDate
     *
     * @param \DateTime $trialEndDate
     *
     * @return Account
     */
    public function setTrialEndDate($trialEndDate)
    {
        $this->trial_end_date = $trialEndDate;

        return $this;
    }

    /**
     * Get trialEndDate
     *
     * @return \DateTime
     */
    public function getTrialEndDate()
    {
        return $this->trial_end_date;
    }

    /**
     * Set isReceivingMarketingEmails
     *
     * @param boolean $isReceivingMarketingEmails
     *
     * @return Account
     */
    public function setIsReceivingMarketingEmails($isReceivingMarketingEmails)
    {
        $this->is_receiving_marketing_emails = $isReceivingMarketingEmails;

        return $this;
    }

    /**
     * Get isReceivingMarketingEmails
     *
     * @return boolean
     */
    public function getIsReceivingMarketingEmails()
    {
        return $this->is_receiving_marketing_emails;
    }

    /**
     * Set isReceivingSecurityEmails
     *
     * @param boolean $isReceivingSecurityEmails
     *
     * @return Account
     */
    public function setIsReceivingSecurityEmails($isReceivingSecurityEmails)
    {
        $this->is_receiving_security_emails = $isReceivingSecurityEmails;

        return $this;
    }

    /**
     * Get isReceivingSecurityEmails
     *
     * @return boolean
     */
    public function getIsReceivingSecurityEmails()
    {
        return $this->is_receiving_security_emails;
    }

    /**
     * Set isReceivingMarketplaceEmails
     *
     * @param boolean $isReceivingMarketplaceEmails
     *
     * @return Account
     */
    public function setIsReceivingMarketplaceEmails($isReceivingMarketplaceEmails)
    {
        $this->is_receiving_marketplace_emails = $isReceivingMarketplaceEmails;

        return $this;
    }

    /**
     * Get isReceivingMarketplaceEmails
     *
     * @return boolean
     */
    public function getIsReceivingMarketplaceEmails()
    {
        return $this->is_receiving_marketplace_emails;
    }

    /**
     * Set isReceivingActivityEmails
     *
     * @param boolean $isReceivingActivityEmails
     *
     * @return Account
     */
    public function setIsReceivingActivityEmails($isReceivingActivityEmails)
    {
        $this->is_receiving_activity_emails = $isReceivingActivityEmails;

        return $this;
    }

    /**
     * Get isReceivingActivityEmails
     *
     * @return boolean
     */
    public function getIsReceivingActivityEmails()
    {
        return $this->is_receiving_activity_emails;
    }

    /**
     * Set isReceivingGeneralEmails
     *
     * @param boolean $isReceivingGeneralEmails
     *
     * @return Account
     */
    public function setIsReceivingGeneralEmails($isReceivingGeneralEmails)
    {
        $this->is_receiving_general_emails = $isReceivingGeneralEmails;

        return $this;
    }

    /**
     * Get isReceivingGeneralEmails
     *
     * @return boolean
     */
    public function getIsReceivingGeneralEmails()
    {
        return $this->is_receiving_general_emails;
    }

    /**
     * Set customerId
     *
     * @param integer $customerId
     *
     * @return Account
     */
    public function setCustomerId($customerId)
    {
        $this->customer_id = $customerId;

        return $this;
    }

    /**
     * Get customerId
     *
     * @return integer
     */
    public function getCustomerId()
    {
        return $this->customer_id;
    }

    /**
     * Set type
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\AccountType $type
     *
     * @return Account
     */
    public function setType(\Tavro\Bundle\CoreBundle\Entity\AccountType $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\AccountType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set structure
     *
     * @param string $structure
     *
     * @return Account
     */
    public function setStructure($structure)
    {
        $this->structure = $structure;

        return $this;
    }

    /**
     * Get structure
     *
     * @return string
     */
    public function getStructure()
    {
        return $this->structure;
    }

    /**
     * Set businessId
     *
     * @param integer $businessId
     *
     * @return Account
     */
    public function setBusinessId($businessId)
    {
        $this->business_id = $businessId;

        return $this;
    }

    /**
     * Get businessId
     *
     * @return integer
     */
    public function getBusinessId()
    {
        return $this->business_id;
    }
}

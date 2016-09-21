<?php

namespace Tavro\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping\Table;
use JMS\Serializer\Annotation\MaxDepth;

use Tavro\Bundle\CoreBundle\Model\Entity;
use Tavro\Bundle\CoreBundle\Model\EntityInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Bundle\CoreBundle\Repository\AccountRepository")
 * @ORM\HasLifecycleCallbacks
 * @Table(name="tavro_account")
 */
class Account extends Entity implements EntityInterface
{
    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\User", inversedBy="accounts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "detail", "simple"})
     * @MaxDepth(1)
     */
    protected $user;

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
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\FundingRound", mappedBy="account", cascade={"remove"})
     */
    protected $funding_rounds;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\Service", mappedBy="account", cascade={"remove"})
     */
    protected $services;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\Revenue", mappedBy="account", cascade={"remove"})
     */
    protected $revenues;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\Product", mappedBy="account", cascade={"remove"})
     */
    protected $products;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\Expense", mappedBy="account", cascade={"remove"})
     */
    protected $expenses;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\Tag", mappedBy="account", cascade={"remove"})
     */
    protected $tags;

    /**
     * @ORM\Column(type="string", unique=TRUE, length=500, nullable=FALSE)
     * @Groups({"api", "detail", "simple"})
     */
    protected $name;

    /**
     * @ORM\Column(type="string", unique=TRUE, length=500, nullable=FALSE)
     * @Groups({"api", "detail", "simple"})
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

    public function createCleanName($name)
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
     * Add tag
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Tag $tag
     *
     * @return Account
     */
    public function addTag(\Tavro\Bundle\CoreBundle\Entity\Tag $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove tag
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Tag $tag
     */
    public function removeTag(\Tavro\Bundle\CoreBundle\Entity\Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
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
}

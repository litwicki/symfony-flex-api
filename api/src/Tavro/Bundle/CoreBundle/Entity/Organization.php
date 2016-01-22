<?php
namespace Tavro\Bundle\CoreBundle\Entity;

use Tavro\Bundle\CoreBundle\Model\ApiEntityInterface;
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

use Tavro\Bundle\CoreBundle\Model\ApiEntity;
use Tavro\Bundle\CoreBundle\Model\EntityInterface;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="tavro_organization")
 *
 */
class Organization extends ApiEntity
{
    /**
     * @ORM\Column(type="string", length=500, nullable=false)
     * @Groups({"api", "tavro", "typeahead"})
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length=500, nullable=false)
     * @Groups({"api", "tavro"})
     */
    protected $body;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\User", inversedBy="organizations")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     * @Groups({"api", "tavro"})
     * @MaxDepth(1)
     */
    protected $owner;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\Node", mappedBy="organization", cascade={"remove"})
     */
    protected $nodes;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\OrganizationShareholder", mappedBy="organization", cascade={"remove"})
     */
    protected $organization_shareholders;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\UserOrganization", mappedBy="organization", cascade={"remove"})
     */
    protected $user_organizations;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\ServiceCategory", mappedBy="organization", cascade={"remove"})
     * @Groups({"tavro"})
     * @MaxDepth(3)
     */
    protected $service_categories;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\ExpenseCategory", mappedBy="organization", cascade={"remove"})
     * @Groups({"tavro"})
     * @MaxDepth(3)
     */
    protected $expense_categories;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\ProductCategory", mappedBy="organization", cascade={"remove"})
     * @Groups({"tavro"})
     * @MaxDepth(3)
     */
    protected $product_categories;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\RevenueCategory", mappedBy="organization", cascade={"remove"})
     * @Groups({"tavro"})
     * @MaxDepth(3)
     */
    protected $revenue_categories;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\Service", mappedBy="organization", cascade={"remove"})
     * @Groups({"tavro"})
     * @MaxDepth(3)
     */
    protected $services;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\Expense", mappedBy="organization", cascade={"remove"})
     * @Groups({"tavro"})
     * @MaxDepth(3)
     */
    protected $expenses;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\Product", mappedBy="organization", cascade={"remove"})
     * @Groups({"tavro"})
     * @MaxDepth(3)
     */
    protected $products;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\Revenue", mappedBy="organization", cascade={"remove"})
     * @Groups({"tavro"})
     * @MaxDepth(3)
     */
    protected $revenues;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\Funding", mappedBy="organization", cascade={"remove"})
     * @Groups({"tavro"})
     * @MaxDepth(3)
     */
    protected $funding;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\Shareholder", mappedBy="organization", cascade={"remove"})
     * @Groups({"tavro"})
     * @MaxDepth(3)
     */
    protected $shareholders;

    /**
     * Set body
     *
     * @param string $body
     * @return Organization
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
     * Set owner
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\User $owner
     * @return User
     */
    public function setOwner(\Tavro\Bundle\CoreBundle\Entity\User $owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Add userOrganization
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\UserOrganization $userOrganization
     *
     * @return Organization
     */
    public function addUserOrganization(\Tavro\Bundle\CoreBundle\Entity\UserOrganization $userOrganization)
    {
        $this->user_organizations[] = $userOrganization;

        return $this;
    }

    /**
     * Remove userOrganization
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\UserOrganization $userOrganization
     */
    public function removeUserOrganization(\Tavro\Bundle\CoreBundle\Entity\UserOrganization $userOrganization)
    {
        $this->user_organizations->removeElement($userOrganization);
    }

    /**
     * Get userOrganizations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserOrganizations()
    {
        return $this->user_organizations;
    }

    /**
     * Add serviceCategory
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\ServiceCategory $serviceCategory
     *
     * @return Organization
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
     * @return Organization
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
     * @return Organization
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
     * @return Organization
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
     * @return Organization
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
     * Add expense
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Expense $expense
     *
     * @return Organization
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
     * Add product
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Product $product
     *
     * @return Organization
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
     * Add revenue
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Revenue $revenue
     *
     * @return Organization
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
     * Add variable
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Variable $variable
     *
     * @return Organization
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
     * Add Funding
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Funding $funding
     *
     * @return Organization
     */
    public function addFunding(\Tavro\Bundle\CoreBundle\Entity\Funding $funding)
    {
        $this->funding[] = $funding;

        return $this;
    }

    /**
     * Remove funding
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Funding $funding
     */
    public function removeFunding(\Tavro\Bundle\CoreBundle\Entity\Funding $funding)
    {
        $this->funding->removeElement($funding);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFunding()
    {
        return $this->funding;
    }

    /**
     * Add organization_shareholders
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\OrganizationShareholder $nodeComments
     * @return Node
     */
    public function addOrganizationShareholder(\Tavro\Bundle\CoreBundle\Entity\OrganizationShareholder $nodeComments)
    {
        $this->organization_shareholders[] = $nodeComments;

        return $this;
    }

    /**
     * Remove organization_shareholders
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\OrganizationShareholder $nodeComments
     */
    public function removeOrganizationShareholder(\Tavro\Bundle\CoreBundle\Entity\OrganizationShareholder $nodeComments)
    {
        $this->organization_shareholders->removeElement($nodeComments);
    }

    /**
     * Get organization_shareholders
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrganizationShareholders()
    {
        return $this->organization_shareholders;
    }

    public function getShareholders()
    {
        $items = array();
        foreach($this->organization_shareholders as $item) {
            $items[] = $item->getShareholder();
        }
        return $items;
    }
}

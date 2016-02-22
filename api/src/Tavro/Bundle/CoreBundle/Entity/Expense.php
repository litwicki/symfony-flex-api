<?php
namespace Tavro\Bundle\CoreBundle\Entity;

use Tavro\Bundle\CoreBundle\Model\Api\ApiEntityInterface;
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

use Tavro\Bundle\CoreBundle\Model\Api\ApiEntity;
use Tavro\Bundle\CoreBundle\Model\EntityInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Bundle\CoreBundle\Repository\ExpenseRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="tavro_expense")
 *
 */
class Expense extends ApiEntity
{

    /**
     * @ORM\Column(type="string", length=8000, nullable=true)
     * @Groups({"api", "tavro", "simple"})
     */
    protected $body;

    /**
     * @ORM\Column(type="float", nullable=false)
     * @Groups({"api", "tavro", "simple"})
     */
    protected $amount;

    /**
     * @ORM\Column(type="datetime", length=1000, nullable=false)
     * @Groups({"api", "tavro", "simple"})
     */
    protected $expense_date;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\ExpenseCategory", inversedBy="expenses")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=false)
     * @Groups({"api", "tavro", "simple"})
     * @MaxDepth(3)
     */
    protected $category;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\User", inversedBy="expenses")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     * @Groups({"api", "tavro", "simple"})
     * @MaxDepth(3)
     */
    protected $user;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\ExpenseComment", mappedBy="expense", cascade={"remove"})
     */
    protected $expense_comments;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\ExpenseTag", mappedBy="expense", cascade={"remove"})
     */
    protected $expense_tags;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Organization", inversedBy="expenses")
     * @ORM\JoinColumn(name="organization_id", referencedColumnName="id", nullable=false)
     * @Groups({"api", "tavro", "simple"})
     * @MaxDepth(3)
     */
    protected $organization;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Organization", inversedBy="expenses")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id", nullable=true)
     * @Groups({"api", "tavro", "simple"})
     * @MaxDepth(3)
     */
    protected $customer;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->expense_tags = new \Doctrine\Common\Collections\ArrayCollection();;
        $this->expense_comments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->status = 1;
        $now = new \DateTime();
        $tz = new \DateTimeZone('America/New_York');
        $now->setTimezone($tz);
        $this->create_date = $now;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return Expense
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
     * Set amount
     *
     * @param float $amount
     *
     * @return Expense
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set expenseDate
     *
     * @param \DateTime $expenseDate
     *
     * @return Expense
     */
    public function setExpenseDate($expenseDate)
    {
        $this->expense_date = $expenseDate;

        return $this;
    }

    /**
     * Get expenseDate
     *
     * @return \DateTime
     */
    public function getExpenseDate()
    {
        return $this->expense_date;
    }

    /**
     * Set category
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\ExpenseCategory $category
     *
     * @return Expense
     */
    public function setCategory(\Tavro\Bundle\CoreBundle\Entity\ExpenseCategory $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\ExpenseCategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set user
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     *
     * @return Expense
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
     * Add expenseComment
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\ExpenseComment $expenseComment
     *
     * @return Expense
     */
    public function addExpenseComment(\Tavro\Bundle\CoreBundle\Entity\ExpenseComment $expenseComment)
    {
        $this->expense_comments[] = $expenseComment;

        return $this;
    }

    /**
     * Remove expenseComment
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\ExpenseComment $expenseComment
     */
    public function removeExpenseComment(\Tavro\Bundle\CoreBundle\Entity\ExpenseComment $expenseComment)
    {
        $this->expense_comments->removeElement($expenseComment);
    }

    /**
     * Get expenseComments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExpenseComments()
    {
        return $this->expense_comments;
    }

    /**
     * Add expenseTag
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\ExpenseTag $expenseTag
     *
     * @return Expense
     */
    public function addExpenseTag(\Tavro\Bundle\CoreBundle\Entity\ExpenseTag $expenseTag)
    {
        $this->expense_tags[] = $expenseTag;

        return $this;
    }

    /**
     * Remove expenseTag
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\ExpenseTag $expenseTag
     */
    public function removeExpenseTag(\Tavro\Bundle\CoreBundle\Entity\ExpenseTag $expenseTag)
    {
        $this->expense_tags->removeElement($expenseTag);
    }

    /**
     * Get expenseTags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExpenseTags()
    {
        return $this->expense_tags;
    }

    /**
     * Set organization
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Organization $organization
     *
     * @return Expense
     */
    public function setOrganization(\Tavro\Bundle\CoreBundle\Entity\Organization $organization)
    {
        $this->organization = $organization;
        return $this;
    }

    /**
     * Get organization
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\Organization
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * Set customer
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Organization $customer
     *
     * @return Expense
     */
    public function setCustomer(\Tavro\Bundle\CoreBundle\Entity\Organization $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\Organization
     */
    public function getCustomer()
    {
        return $this->customer;
    }
}

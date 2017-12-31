<?php
namespace App\Entity;

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

use App\Model\Entity\Entity;
use App\Model\EntityInterface\AccountEntityInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Repository\AccountEntity\ExpenseRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="tavro_expense")
 * @ExclusionPolicy("all")
 *
 */
class Expense extends Entity implements AccountEntityInterface
{
    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\Account", inversedBy="expenses")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"detail"})
     * @MaxDepth(1)
     */
    protected $account;

    /**
     * @ORM\Column(type="float", nullable=FALSE, options={"default" : 0})
     * @Groups({"api", "detail", "simple"})
     * @Assert\GreaterThan(
     *     value = 0,
     *     message = "Expense Amount must be greater than 0"
     * )
     * @Expose
     */
    protected $amount;

    /**
     * @ORM\Column(type="datetime", nullable=FALSE)
     * @Groups({"api", "detail", "simple"})
     * @Expose
     */
    protected $expense_date;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\ExpenseCategory", inversedBy="expenses")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "detail", "simple"})
     * @MaxDepth(2)
     * @Expose
     */
    protected $category;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\User", inversedBy="expenses")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "detail", "simple"})
     * @MaxDepth(1)
     * @Expose
     */
    protected $user;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Entity\ExpenseComment", mappedBy="expense", cascade={"remove"})
     */
    protected $expense_comments;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Entity\ExpenseTag", mappedBy="expense", cascade={"remove"})
     */
    protected $expense_tags;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->expense_tags = new \Doctrine\Common\Collections\ArrayCollection();;
        $this->expense_comments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->expense_date = new \DateTime();
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
     * @param \App\Entity\ExpenseCategory $category
     *
     * @return Expense
     */
    public function setCategory(\Tavro\Entity\ExpenseCategory $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Tavro\Entity\ExpenseCategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set user
     *
     * @param \App\Entity\User $user
     *
     * @return Expense
     */
    public function setUser(\Tavro\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Tavro\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add expenseComment
     *
     * @param \App\Entity\ExpenseComment $expenseComment
     *
     * @return Expense
     */
    public function addExpenseComment(\Tavro\Entity\ExpenseComment $expenseComment)
    {
        $this->expense_comments[] = $expenseComment;

        return $this;
    }

    /**
     * Remove expenseComment
     *
     * @param \App\Entity\ExpenseComment $expenseComment
     */
    public function removeExpenseComment(\Tavro\Entity\ExpenseComment $expenseComment)
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
     * @param \App\Entity\ExpenseTag $expenseTag
     *
     * @return Expense
     */
    public function addExpenseTag(\Tavro\Entity\ExpenseTag $expenseTag)
    {
        $this->expense_tags[] = $expenseTag;

        return $this;
    }

    /**
     * Remove expenseTag
     *
     * @param \App\Entity\ExpenseTag $expenseTag
     */
    public function removeExpenseTag(\Tavro\Entity\ExpenseTag $expenseTag)
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
     * Set account
     *
     * @param \App\Entity\Account $account
     *
     * @return Expense
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

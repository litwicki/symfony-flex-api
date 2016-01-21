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

    public function __toString()
    {
        return $this->title;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Expense
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set body
     *
     * @param string $body
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
     * Set user
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
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
     * Set organization
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Organization $organization
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
     * Add expense_comments
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\ExpenseComment $expenseComments
     * @return Expense
     */
    public function addExpenseComment(\Tavro\Bundle\CoreBundle\Entity\ExpenseComment $expenseComments)
    {
        $this->expense_comments[] = $expenseComments;

        return $this;
    }

    /**
     * Remove expense_comments
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\ExpenseComment $expenseComments
     */
    public function removeExpenseComment(\Tavro\Bundle\CoreBundle\Entity\ExpenseComment $expenseComments)
    {
        $this->expense_comments->removeElement($expenseComments);
    }

    /**
     * Get expense_comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExpenseComments()
    {
        return $this->expense_comments;
    }

    /**
     * @VirtualProperty
     * @SerializedName("expense_comments")
     * @Groups({"api", "tavro","detail"})
     * @MaxDepth(2)
     */
    public function getComments()
    {
        $comments = new \Doctrine\Common\Collections\ArrayCollection();
        foreach($this->expense_comments as $expenseComment) {
            $comments->add($expenseComment->getComment());
        }
        return $comments;
    }
    /**
     * @VirtualProperty
     * @SerializedName("comment_count")
     * @Groups({"api", "tavro", "simple"})
     * @return int
     */
    public function getCommentCount()
    {
        return $this->expense_comments->count();
    }

    /**
     * @VirtualProperty
     * @SerializedName("expense_tags")
     * @Groups({"api", "tavro","detail"})
     * @MaxDepth(2)
     */
    public function getTags()
    {
        $tags = new \Doctrine\Common\Collections\ArrayCollection();
        foreach($this->expense_tags as $expenseTag) {
            $tags->add($expenseTag->getTag());
        }
        return $tags;
    }
    
    /**
     * Add expense_tags
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\ExpenseTag $expenseTags
     * @return Expense
     */
    public function addExpenseTag(\Tavro\Bundle\CoreBundle\Entity\ExpenseTag $expenseTags)
    {
        $this->expense_tags[] = $expenseTags;

        return $this;
    }

    /**
     * Remove expense_tags
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\ExpenseTag $expenseTags
     */
    public function removeExpenseTag(\Tavro\Bundle\CoreBundle\Entity\ExpenseTag $expenseTags)
    {
        $this->expense_tags->removeElement($expenseTags);
    }

    /**
     * Get expense_tags
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getExpenseTags()
    {
        return $this->expense_tags;
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
}

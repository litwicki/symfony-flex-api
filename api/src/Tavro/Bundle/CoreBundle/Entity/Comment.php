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
use Doctrine\ORM\Mapping\Table;
use JMS\Serializer\Annotation\MaxDepth;

use Tavro\Bundle\CoreBundle\Model\ApiEntity;
use Tavro\Bundle\CoreBundle\Model\EntityInterface;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @Table(name="tavro_comment")
 */
class Comment extends ApiEntity
{

    /**
     * @ORM\Column(type="string", length=8000, nullable=true)
     * @Groups({"api", "tavro", "simple"})
     */
    protected $body;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\NodeComment", mappedBy="comment", cascade={"remove"})
     */
    protected $node_comments;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\ExpenseComment", mappedBy="comment", cascade={"remove"})
     */
    protected $expense_comments;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\RevenueComment", mappedBy="comment", cascade={"remove"})
     */
    protected $revenue_comments;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\CustomerComment", mappedBy="comment", cascade={"remove"})
     */
    protected $customer_comments;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\FundingRoundComment", mappedBy="comment", cascade={"remove"})
     */
    protected $funding_round_comments;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\User", inversedBy="comments")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @Groups({"api", "tavro", "simple"})
     * @MaxDepth(2)
     */
    protected $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->node_comments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->status = 1;
        $this->create_date = new \DateTime();
    }

    public function __toString()
    {
        return $this->title;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return Comment
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
     * Add node_comments
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\NodeComment $nodeComments
     * @return Comment
     */
    public function addNodeComment(\Tavro\Bundle\CoreBundle\Entity\NodeComment $nodeComments)
    {
        $this->node_comments[] = $nodeComments;

        return $this;
    }

    /**
     * Remove node_comments
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\NodeComment $nodeComments
     */
    public function removeNodeComment(\Tavro\Bundle\CoreBundle\Entity\NodeComment $nodeComments)
    {
        $this->node_comments->removeElement($nodeComments);
    }

    /**
     * Get node_comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNodeComments()
    {
        return $this->node_comments;
    }

    /**
     * Set user
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     * @return Comment
     */
    public function setUser(\Tavro\Bundle\CoreBundle\Entity\User $user = null)
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
     * @return Comment
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
     * Add revenueComment
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\RevenueComment $revenueComment
     *
     * @return Comment
     */
    public function addRevenueComment(\Tavro\Bundle\CoreBundle\Entity\RevenueComment $revenueComment)
    {
        $this->revenue_comments[] = $revenueComment;

        return $this;
    }

    /**
     * Remove revenueComment
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\RevenueComment $revenueComment
     */
    public function removeRevenueComment(\Tavro\Bundle\CoreBundle\Entity\RevenueComment $revenueComment)
    {
        $this->revenue_comments->removeElement($revenueComment);
    }

    /**
     * Get revenueComments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRevenueComments()
    {
        return $this->revenue_comments;
    }

    /**
     * Add funding_roundComment
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\FundingRoundComment $funding_round_comment
     *
     * @return Comment
     */
    public function addFundingRoundComment(\Tavro\Bundle\CoreBundle\Entity\FundingRoundComment $funding_round_comment)
    {
        $this->funding_round_comments[] = $funding_round_comment;

        return $this;
    }

    /**
     * Remove funding_roundComment
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\FundingRoundComment $funding_round_comment
     */
    public function removeFundingRoundComment(\Tavro\Bundle\CoreBundle\Entity\FundingRoundComment $funding_round_comment)
    {
        $this->funding_round_comments->removeElement($funding_round_comment);
    }

    /**
     * Get funding_roundComments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFundingRoundComments()
    {
        return $this->funding_round_comments;
    }

    /**
     * Add customerComment
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\CustomerComment $customerComment
     *
     * @return Comment
     */
    public function addCustomerComment(\Tavro\Bundle\CoreBundle\Entity\CustomerComment $customerComment)
    {
        $this->customer_comments[] = $customerComment;

        return $this;
    }

    /**
     * Remove customerComment
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\CustomerComment $customerComment
     */
    public function removeCustomerComment(\Tavro\Bundle\CoreBundle\Entity\CustomerComment $customerComment)
    {
        $this->customer_comments->removeElement($customerComment);
    }

    /**
     * Get customerComments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCustomerComments()
    {
        return $this->customer_comments;
    }
}

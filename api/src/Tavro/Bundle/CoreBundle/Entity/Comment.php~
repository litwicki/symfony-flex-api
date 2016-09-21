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
 * @ORM\Entity(repositoryClass="Tavro\Bundle\CoreBundle\Repository\CommentRepository")
 * @ORM\HasLifecycleCallbacks
 * @Table(name="tavro_comment")
 */
class Comment extends Entity implements EntityInterface
{
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
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\FundingRoundComment", mappedBy="comment", cascade={"remove"})
     */
    protected $funding_round_comments;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\OrganizationComment", mappedBy="comment", cascade={"remove"})
     */
    protected $organization_comments;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\User", inversedBy="comments")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @Groups({"api", "detail", "simple"})
     * @MaxDepth(1)
     */
    protected $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->node_comments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->expense_comments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->revenue_comments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->organization_comments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->funding_round_comments = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return sprintf('Comment %s', $this->id);
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
     * Add OrganizationComment
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\OrganizationComment $organizationComment
     *
     * @return Comment
     */
    public function addOrganizationComment(\Tavro\Bundle\CoreBundle\Entity\OrganizationComment $organizationComment)
    {
        $this->organization_comments[] = $organizationComment;

        return $this;
    }

    /**
     * Remove OrganizationComment
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\OrganizationComment $organizationComment
     */
    public function removeOrganizationComment(\Tavro\Bundle\CoreBundle\Entity\OrganizationComment $organizationComment)
    {
        $this->organization_comments->removeElement($organizationComment);
    }

    /**
     * Get organizationComments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrganizationComments()
    {
        return $this->organization_comments;
    }
}

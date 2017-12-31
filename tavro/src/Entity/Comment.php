<?php

namespace App\Entity;

use Doctrine\ORM\Mapping AS ORM;

use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping\Table;
use JMS\Serializer\Annotation\MaxDepth;

use App\Model\Entity\Entity;
use App\Model\EntityInterface\EntityInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Repository\Entity\CommentRepository")
 * @ORM\HasLifecycleCallbacks
 * @Table(name="tavro_comment")
 * @ExclusionPolicy("all")
 */
class Comment extends Entity implements EntityInterface
{
    /**
     * @ORM\OneToMany(targetEntity="Tavro\Entity\NodeComment", mappedBy="comment", cascade={"remove"})
     */
    protected $node_comments;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Entity\ExpenseComment", mappedBy="comment", cascade={"remove"})
     */
    protected $expense_comments;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Entity\RevenueComment", mappedBy="comment", cascade={"remove"})
     */
    protected $revenue_comments;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Entity\FundingRoundComment", mappedBy="comment", cascade={"remove"})
     */
    protected $funding_round_comments;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Entity\OrganizationComment", mappedBy="comment", cascade={"remove"})
     */
    protected $organization_comments;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Entity\ForecastComment", mappedBy="comment", cascade={"remove"})
     */
    protected $forecast_comments;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\User", inversedBy="comments")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     * @Groups({"api", "detail", "simple"})
     * @MaxDepth(1)
     * @Expose
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
     * @param \App\Entity\NodeComment $nodeComments
     * @return Comment
     */
    public function addNodeComment(\Tavro\Entity\NodeComment $nodeComments)
    {
        $this->node_comments[] = $nodeComments;

        return $this;
    }

    /**
     * Remove node_comments
     *
     * @param \App\Entity\NodeComment $nodeComments
     */
    public function removeNodeComment(\Tavro\Entity\NodeComment $nodeComments)
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
     * @param \App\Entity\User $user
     * @return Comment
     */
    public function setUser(\Tavro\Entity\User $user = null)
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
     * @return Comment
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
     * Add revenueComment
     *
     * @param \App\Entity\RevenueComment $revenueComment
     *
     * @return Comment
     */
    public function addRevenueComment(\Tavro\Entity\RevenueComment $revenueComment)
    {
        $this->revenue_comments[] = $revenueComment;

        return $this;
    }

    /**
     * Remove revenueComment
     *
     * @param \App\Entity\RevenueComment $revenueComment
     */
    public function removeRevenueComment(\Tavro\Entity\RevenueComment $revenueComment)
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
     * @param \App\Entity\FundingRoundComment $funding_round_comment
     *
     * @return Comment
     */
    public function addFundingRoundComment(\Tavro\Entity\FundingRoundComment $funding_round_comment)
    {
        $this->funding_round_comments[] = $funding_round_comment;

        return $this;
    }

    /**
     * Remove funding_roundComment
     *
     * @param \App\Entity\FundingRoundComment $funding_round_comment
     */
    public function removeFundingRoundComment(\Tavro\Entity\FundingRoundComment $funding_round_comment)
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
     * @param \App\Entity\OrganizationComment $organizationComment
     *
     * @return Comment
     */
    public function addOrganizationComment(\Tavro\Entity\OrganizationComment $organizationComment)
    {
        $this->organization_comments[] = $organizationComment;

        return $this;
    }

    /**
     * Remove OrganizationComment
     *
     * @param \App\Entity\OrganizationComment $organizationComment
     */
    public function removeOrganizationComment(\Tavro\Entity\OrganizationComment $organizationComment)
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

    /**
     * Add forecastComment
     *
     * @param \App\Entity\ForecastComment $forecastComment
     *
     * @return Comment
     */
    public function addForecastComment(\Tavro\Entity\ForecastComment $forecastComment)
    {
        $this->forecast_comments[] = $forecastComment;

        return $this;
    }

    /**
     * Remove forecastComment
     *
     * @param \App\Entity\ForecastComment $forecastComment
     */
    public function removeForecastComment(\Tavro\Entity\ForecastComment $forecastComment)
    {
        $this->forecast_comments->removeElement($forecastComment);
    }

    /**
     * Get forecastComments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getForecastComments()
    {
        return $this->forecast_comments;
    }
}

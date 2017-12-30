<?php

namespace Tavro\Entity;

use Doctrine\ORM\Mapping AS ORM;

use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\MaxDepth;
use Doctrine\ORM\Mapping\Table;

use Tavro\Model\Entity\Entity;
use Tavro\Model\EntityInterface\EntityInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Repository\Entity\ExpenseCommentRepository")
 * @ORM\Table(name="tavro_expense_comment", indexes={@ORM\Index(name="NODE_COMMENT", columns={"comment_id","expense_id"})})
 * @ExclusionPolicy("all")
 */
class ExpenseComment extends Entity implements EntityInterface
{
    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\Comment", inversedBy="expense_comments")
     * @ORM\JoinColumn(name="comment_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "detail"})
     */
    protected $comment;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\Expense", inversedBy="expense_comments")
     * @ORM\JoinColumn(name="expense_id", referencedColumnName="id", nullable=FALSE)
     */
    protected $expense;

    /**
     * Set comment
     *
     * @param \Tavro\Entity\Comment $comment
     * @return ExpenseComment
     */
    public function setComment(\Tavro\Entity\Comment $comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return \Tavro\Entity\Comment
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set Expense
     *
     * @param \Tavro\Entity\Expense $expense
     * @return ExpenseComment
     */
    public function setExpense(\Tavro\Entity\Expense $expense)
    {
        $this->expense = $expense;

        return $this;
    }

    /**
     * Get Expense
     *
     * @return \Tavro\Entity\Expense
     */
    public function getExpense()
    {
        return $this->expense;
    }


    /**
     * Set body
     *
     * @param string $body
     *
     * @return ExpenseComment
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
}

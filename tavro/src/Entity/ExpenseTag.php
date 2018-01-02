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
use Doctrine\ORM\Mapping\Table;

use App\Model\Entity\Entity;
use App\Model\EntityInterface\EntityInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Repository\Entity\ExpenseTagRepository")
 * @ORM\Table(name="tavro_expense_tag", indexes={@ORM\Index(name="EXPENSE_TAG", columns={"tag_id","expense_id"})})
 * @ExclusionPolicy("all")
 */
class ExpenseTag extends Entity implements EntityInterface
{
    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\Tag", inversedBy="expense_tags")
     * @ORM\JoinColumn(name="tag_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "detail"})
     */
    protected $tag;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\Expense", inversedBy="expense_tags")
     * @ORM\JoinColumn(name="expense_id", referencedColumnName="id", nullable=FALSE)
     */
    protected $expense;

    /**
     * Set tag
     *
     * @param \App\Entity\Tag $tag
     * @return ExpenseTag
     */
    public function setTag(\Tavro\Entity\Tag $tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return \Tavro\Entity\Tag
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Set Expense
     *
     * @param \App\Entity\Expense $expense
     * @return ExpenseTag
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
     * @return ExpenseTag
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
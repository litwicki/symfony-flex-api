<?php

namespace Tavro\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\MaxDepth;
use Doctrine\ORM\Mapping\Table;

use Tavro\Bundle\CoreBundle\Model\Entity;
use Tavro\Bundle\CoreBundle\Model\EntityInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Bundle\CoreBundle\Repository\ExpenseTagRepository")
 * @ORM\Table(name="tavro_expense_tag", indexes={@ORM\Index(name="EXPENSE_TAG", columns={"tag_id","expense_id"})})
 * @ExclusionPolicy("all")
 */
class ExpenseTag extends Entity implements EntityInterface
{
    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Tag", inversedBy="expense_tags")
     * @ORM\JoinColumn(name="tag_id", referencedColumnName="id", nullable=false)
     * @Groups({"api", "tavro"})
     */
    protected $tag;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Expense", inversedBy="expense_tags")
     * @ORM\JoinColumn(name="expense_id", referencedColumnName="id", nullable=false)
     */
    protected $expense;

    /**
     * Set tag
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Tag $tag
     * @return ExpenseTag
     */
    public function setTag(\Tavro\Bundle\CoreBundle\Entity\Tag $tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\Tag
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Set Expense
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Expense $expense
     * @return ExpenseTag
     */
    public function setExpense(\Tavro\Bundle\CoreBundle\Entity\Expense $expense)
    {
        $this->expense = $expense;

        return $this;
    }

    /**
     * Get Expense
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\Expense
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

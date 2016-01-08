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
 * @ORM\Table(name="tavro_expense_tag", indexes={@ORM\Index(name="EXPENSE_TAG", columns={"tag_id","expense_id"})})
 * @ExclusionPolicy("all")
 */
class RevenueTag extends Entity
{
    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Tag", inversedBy="expense_tags")
     * @ORM\JoinColumn(name="tag_id", referencedColumnName="id", nullable=false)
     * @Groups({"api", "tavro"})
     */
    protected $tag;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Revenue", inversedBy="expense_tags")
     * @ORM\JoinColumn(name="expense_id", referencedColumnName="id", nullable=false)
     */
    protected $expense;

    /**
     * Set tag
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Tag $tag
     * @return RevenueTag
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
     * Set Revenue
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Revenue $expense
     * @return RevenueTag
     */
    public function setRevenue(\Tavro\Bundle\CoreBundle\Entity\Revenue $expense)
    {
        $this->expense = $expense;

        return $this;
    }

    /**
     * Get Revenue
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\Revenue
     */
    public function getRevenue()
    {
        return $this->expense;
    }

}

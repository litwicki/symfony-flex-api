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
use JMS\Serializer\Annotation\SerializedName;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Validator\Constraints as Assert;

use Tavro\Bundle\CoreBundle\Model\AccountEntity;
use Tavro\Bundle\CoreBundle\Model\AccountEntityInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Bundle\CoreBundle\Repository\TagRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="tavro_tag")
 *
 */
class Tag extends AccountEntity implements AccountEntityInterface
{
    /**
     * @ORM\Column(type="string", length=30, nullable=FALSE)
     * @Groups({"api", "detail", "simple", "typeahead"})
     */
    protected $tag;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\NodeTag", mappedBy="tag", cascade={"remove"})
     */
    protected $node_tags;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\ExpenseTag", mappedBy="tag", cascade={"remove"})
     */
    protected $expense_tags;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\RevenueTag", mappedBy="tag", cascade={"remove"})
     */
    protected $revenue_tags;

    /**
     * Set tag
     *
     * @param string $tag
     * @return Tag
     */
    public function setTag($tag)
    {
        $this->tag = strtolower($tag);

        return $this;
    }

    /**
     * Get tag
     *
     * @return string 
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return Tag
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
     * Add nodeTag
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\NodeTag $nodeTag
     *
     * @return Tag
     */
    public function addNodeTag(\Tavro\Bundle\CoreBundle\Entity\NodeTag $nodeTag)
    {
        $this->node_tags[] = $nodeTag;

        return $this;
    }

    /**
     * Remove nodeTag
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\NodeTag $nodeTag
     */
    public function removeNodeTag(\Tavro\Bundle\CoreBundle\Entity\NodeTag $nodeTag)
    {
        $this->node_tags->removeElement($nodeTag);
    }

    /**
     * Get nodeTags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNodeTags()
    {
        return $this->node_tags;
    }

    /**
     * Add expenseTag
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\ExpenseTag $expenseTag
     *
     * @return Tag
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

}

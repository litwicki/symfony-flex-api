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
 * @ORM\Entity(repositoryClass="Tavro\Bundle\CoreBundle\Repository\RevenueTagRepository")
 * @ORM\Table(name="tavro_revenue_tag", indexes={@ORM\Index(name="REVENUE_TAG", columns={"tag_id","revenue_id"})})
 * @ExclusionPolicy("all")
 */
class RevenueTag extends Entity implements EntityInterface
{
    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Tag", inversedBy="revenue_tags")
     * @ORM\JoinColumn(name="tag_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "tavro"})
     */
    protected $tag;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Revenue", inversedBy="revenue_tags")
     * @ORM\JoinColumn(name="revenue_id", referencedColumnName="id", nullable=FALSE)
     */
    protected $revenue;

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
     * @param \Tavro\Bundle\CoreBundle\Entity\Revenue $revenue
     * @return RevenueTag
     */
    public function setRevenue(\Tavro\Bundle\CoreBundle\Entity\Revenue $revenue)
    {
        $this->revenue = $revenue;

        return $this;
    }

    /**
     * Get Revenue
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\Revenue
     */
    public function getRevenue()
    {
        return $this->revenue;
    }


    /**
     * Set body
     *
     * @param string $body
     *
     * @return RevenueTag
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

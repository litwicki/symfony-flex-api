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
 * @ORM\Entity(repositoryClass="Tavro\Repository\Entity\RevenueTagRepository")
 * @ORM\Table(name="tavro_revenue_tag", indexes={@ORM\Index(name="REVENUE_TAG", columns={"tag_id","revenue_id"})})
 * @ExclusionPolicy("all")
 */
class RevenueTag extends Entity implements EntityInterface
{
    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\Tag", inversedBy="revenue_tags")
     * @ORM\JoinColumn(name="tag_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "detail"})
     */
    protected $tag;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\Revenue", inversedBy="revenue_tags")
     * @ORM\JoinColumn(name="revenue_id", referencedColumnName="id", nullable=FALSE)
     */
    protected $revenue;

    /**
     * Set tag
     *
     * @param \App\Entity\Tag $tag
     * @return RevenueTag
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
     * Set Revenue
     *
     * @param \App\Entity\Revenue $revenue
     * @return RevenueTag
     */
    public function setRevenue(\Tavro\Entity\Revenue $revenue)
    {
        $this->revenue = $revenue;

        return $this;
    }

    /**
     * Get Revenue
     *
     * @return \Tavro\Entity\Revenue
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

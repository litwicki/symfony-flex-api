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
 * @ORM\Entity(repositoryClass="Tavro\Bundle\CoreBundle\Doctrine\Repository\Entity\CustomerCommentRepository")
 * @ORM\Table(name="tavro_customer_comment", indexes={@ORM\Index(name="CUSTOMER_COMMENT", columns={"comment_id","customer_id"})})
 * @ExclusionPolicy("all")
 */
class CustomerComment extends Entity implements EntityInterface
{
    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Comment", inversedBy="customer_comments")
     * @ORM\JoinColumn(name="comment_id", referencedColumnName="id", nullable=false)
     * @Groups({"api", "tavro"})
     */
    protected $comment;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Customer", inversedBy="customer_comments")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id", nullable=false)
     */
    protected $customer;

    /**
     * Set comment
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Comment $comment
     * @return CustomerComment
     */
    public function setComment(\Tavro\Bundle\CoreBundle\Entity\Comment $comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\Comment
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set Customer
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Customer $customer
     * @return CustomerComment
     */
    public function setCustomer(\Tavro\Bundle\CoreBundle\Entity\Customer $customer)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get Customer
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }


    /**
     * Set body
     *
     * @param string $body
     *
     * @return CustomerComment
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

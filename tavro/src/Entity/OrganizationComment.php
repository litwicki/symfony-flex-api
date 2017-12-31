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
use JMS\Serializer\Annotation\SerializedName;

use App\Model\Entity\Entity;
use App\Model\EntityInterface\EntityInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Repository\Entity\OrganizationCommentRepository")
 * @ORM\Table(name="tavro_organization_comment", indexes={@ORM\Index(name="ORGANIZATION_COMMENT", columns={"comment_id","organization_id"})})
 * @ExclusionPolicy("all")
 */
class OrganizationComment extends Entity implements EntityInterface
{
    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\Comment", inversedBy="organization_comments")
     * @ORM\JoinColumn(name="comment_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "detail"})
     */
    protected $comment;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\Organization", inversedBy="organization_comments")
     * @ORM\JoinColumn(name="organization_id", referencedColumnName="id", nullable=FALSE)
     */
    protected $organization;

    /**
     * Set comment
     *
     * @param \App\Entity\Comment $comment
     * @return OrganizationComment
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
     * Set Organization
     *
     * @param \App\Entity\Organization $organization
     * @return OrganizationComment
     */
    public function setOrganization(\Tavro\Entity\Organization $organization)
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * Get Organization
     *
     * @return \Tavro\Entity\Organization
     */
    public function getOrganization()
    {
        return $this->organization;
    }


    /**
     * Set body
     *
     * @param string $body
     *
     * @return OrganizationComment
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
     * @VirtualProperty
     * @SerializedName("comments")
     */
    public function getComments()
    {
        $comments = new \Doctrine\Common\Collections\ArrayCollection();
        foreach($this->organization_comments as $organization_comment) {
            $comments->add($organization_comment->getComment());
        }
        return $comments;
    }

}

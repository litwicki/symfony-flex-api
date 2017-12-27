<?php

namespace Tavro\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Rhumsaa\Uuid\Uuid;

use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\XmlRoot;
use JMS\Serializer\Annotation\XmlNamespace;
use JMS\Serializer\Annotation\SerializedName;
use Doctrine\ORM\Mapping\Table;

use Symfony\Component\Validator\Constraints as Assert;

use Tavro\Bundle\CoreBundle\Model\Entity\Entity;
use Tavro\Bundle\CoreBundle\Model\EntityInterface\EntityInterface;
use Tavro\Bundle\CoreBundle\Model\EventInterface\TavroCreateEventInterface;
use Tavro\Bundle\CoreBundle\Model\EventInterface\TavroDeleteEventInterface;
use Tavro\Bundle\CoreBundle\Model\EventInterface\TavroUpdateEventInterface;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Bundle\CoreBundle\Repository\Entity\SyndicateRepository")
 * @Table(name="tavro_syndicate")
 *
 * @XmlRoot("syndicate")
 * @XmlNamespace(uri="http://tavro.io/api/syndicates")
 * @ExclusionPolicy("all")
 */
class Syndicate extends Entity implements EntityInterface, TavroCreateEventInterface, TavroDeleteEventInterface, TavroUpdateEventInterface
{

    const CREATE_EVENT_CLASS = 'SyndicateCreateEvent';
    const DELETE_EVENT_CLASS = 'SyndicateDeleteEvent';
    const UPDATE_EVENT_CLASS = 'SyndicateUpdateEvent';

    /**
     * @ORM\Column(type="string", length=255, nullable=FALSE)
     * @Groups({"api", "detail", "simple"})
     * @Expose
     */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\User", inversedBy="syndicates")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "detail", "simple"})
     * @MaxDepth(3)
     * @Expose
     */
    protected $user;

    /**
     * @ORM\ManyToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\User", inversedBy="syndicate_memberships")
     * @ORM\JoinTable(
     *     name="tavro_syndicate_investor",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=FALSE)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="syndicate_id", referencedColumnName="id", nullable=FALSE)}
     * )
     * @Groups({"detail"})
     * @MaxDepth(3)
     * @Expose
     */
    protected $investors;


    /**
     * Set name
     *
     * @param string $name
     *
     * @return Syndicate
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return Syndicate
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
     * Set user
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     *
     * @return Syndicate
     */
    public function setUser(\Tavro\Bundle\CoreBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add investor
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\User $investor
     *
     * @return Syndicate
     */
    public function addInvestor(\Tavro\Bundle\CoreBundle\Entity\User $investor)
    {
        $this->investors[] = $investor;

        return $this;
    }

    /**
     * Remove investor
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\User $investor
     */
    public function removeInvestor(\Tavro\Bundle\CoreBundle\Entity\User $investor)
    {
        $this->investors->removeElement($investor);
    }

    /**
     * Get investors
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInvestors()
    {
        return $this->investors;
    }
}

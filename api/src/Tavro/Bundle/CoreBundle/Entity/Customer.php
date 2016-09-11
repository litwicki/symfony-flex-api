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

use Tavro\Bundle\CoreBundle\Model\UserInterface;
use Tavro\Bundle\CoreBundle\Model\OrganizationEntity;
use Tavro\Bundle\CoreBundle\Model\OrganizationEntityInterface;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Bundle\CoreBundle\Doctrine\Repository\Entity\CustomerRepository")
 * @Table(name="tavro_customer")
 *
 * @XmlRoot("shareholder")
 * @XmlNamespace(uri="http://tavro.io/api/shareholders")
 */
class Customer extends OrganizationEntity implements OrganizationEntityInterface
{

    /**
     * @ORM\Column(type="string", length=255, nullable=TRUE)
     * @Groups({"api", "tavro", "simple", "typeahead"})
     */
    protected $job_title;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=TRUE)
     * @Groups({"api", "tavro", "simple"})
     * @MaxDepth(1)
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Person")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id", nullable=TRUE)
     * @Groups({"api", "tavro", "simple"})
     * @MaxDepth(1)
     */
    protected $person;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\CustomerComment", mappedBy="customer", cascade={"remove"})
     */
    protected $customer_comments;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->create_date = new \DateTime();
        $this->update_date = new \DateTime();
    }

    public function __toString()
    {
        return sprintf('%s, %s', $this->last_name, $this->first_name);
    }



    /**
     * Set jobTitle
     *
     * @param string $jobTitle
     *
     * @return Customer
     */
    public function setJobTitle($jobTitle)
    {
        $this->job_title = $jobTitle;

        return $this;
    }

    /**
     * Get jobTitle
     *
     * @return string
     */
    public function getJobTitle()
    {
        return $this->job_title;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return Customer
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
     * @return Customer
     */
    public function setUser(\Tavro\Bundle\CoreBundle\Entity\User $user = null)
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
     * Add customerComment
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\CustomerComment $customerComment
     *
     * @return Customer
     */
    public function addCustomerComment(\Tavro\Bundle\CoreBundle\Entity\CustomerComment $customerComment)
    {
        $this->customer_comments[] = $customerComment;

        return $this;
    }

    /**
     * Remove customerComment
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\CustomerComment $customerComment
     */
    public function removeCustomerComment(\Tavro\Bundle\CoreBundle\Entity\CustomerComment $customerComment)
    {
        $this->customer_comments->removeElement($customerComment);
    }

    /**
     * Get customerComments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCustomerComments()
    {
        return $this->customer_comments;
    }


    /**
     * Set person
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Person $person
     *
     * @return Customer
     */
    public function setPerson(\Tavro\Bundle\CoreBundle\Entity\Person $person = null)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\Person
     */
    public function getPerson()
    {
        return $this->person;
    }
}

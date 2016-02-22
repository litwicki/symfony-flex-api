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
use Tavro\Bundle\CoreBundle\Model\Entity;
use Tavro\Bundle\CoreBundle\Model\EntityInterface;
use JMS\Serializer\Annotation\MaxDepth;
use Tavro\Bundle\CoreBundle\Model\Api\ApiEntity;

/**
 * @ORM\Entity
 * @Table(name="tavro_customer")
 *
 * @XmlRoot("shareholder")
 * @XmlNamespace(uri="http://tavro.io/api/shareholders")
 */
class Customer extends Entity
{
    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Groups({"api", "tavro", "simple", "typeahead"})
     */
    protected $first_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Groups({"api", "tavro", "simple", "typeahead"})
     */
    protected $last_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"api", "tavro", "simple", "typeahead"})
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Groups({"api", "tavro", "simple"})
     */
    protected $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"api", "tavro", "simple"})
     */
    protected $address2;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Groups({"api", "tavro", "simple"})
     */
    protected $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Groups({"api", "tavro", "simple"})
     */
    protected $state;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Groups({"api", "tavro", "simple"})
     */
    protected $zip;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Groups({"api", "tavro", "simple", "typeahead"})
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Groups({"api", "tavro", "simple", "typeahead"})
     */
    protected $phone;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\User", inversedBy="shareholders")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     * @Groups({"api", "tavro", "simple"})
     * @MaxDepth(1)
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Organization", inversedBy="nodes")
     * @ORM\JoinColumn(name="organization_id", referencedColumnName="id", nullable=false)
     * @Groups({"api", "tavro", "simple"})
     * @MaxDepth(3)
     */
    protected $organization;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\CustomerComment", mappedBy="customer", cascade={"remove"})
     */
    protected $customer_comments;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->create_date = new \DateTime();
        $this->update_date = new \DateTime();
    }

    public function __toString()
    {
        return sprintf('%s, %s', $this->last_name, $this->first_name);
    }

    /**
     * Set notes
     *
     * @param string $notes
     * @return Node
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * Get notes
     *
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Shareholder
     */
    public function setFirstName($firstName)
    {
        $this->first_name = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Shareholder
     */
    public function setLastName($lastName)
    {
        $this->last_name = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Shareholder
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Shareholder
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set address2
     *
     * @param string $address2
     *
     * @return Shareholder
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;

        return $this;
    }

    /**
     * Get address2
     *
     * @return string
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Shareholder
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set state
     *
     * @param string $state
     *
     * @return Shareholder
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set zip
     *
     * @param string $zip
     *
     * @return Shareholder
     */
    public function setZip($zip)
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * Get zip
     *
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Shareholder
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set user
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     * @return Node
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
     * Set phone
     *
     * @param string $phone
     *
     * @return Shareholder
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
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
     * Set organization
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Organization $organization
     *
     * @return Customer
     */
    public function setOrganization(\Tavro\Bundle\CoreBundle\Entity\Organization $organization)
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * Get organization
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\Organization
     */
    public function getOrganization()
    {
        return $this->organization;
    }
}

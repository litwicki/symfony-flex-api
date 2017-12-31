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
use JMS\Serializer\Annotation\SerializedName;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Validator\Constraints as Assert;

use App\Model\Entity\Entity;
use App\Model\EntityInterface\AccountEntityInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Repository\AccountEntity\OrganizationRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="tavro_organization")
 * @ExclusionPolicy("all")
 *
 */
class Organization extends Entity implements AccountEntityInterface
{
    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\Account", inversedBy="organizations")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"detail"})
     * @MaxDepth(1)
     * @Expose
     */
    protected $account;

    /**
     * @ORM\Column(type="integer", length=255, unique=TRUE, nullable=TRUE)
     * @Groups({"api", "detail", "simple"})
     * @Expose
     */
    protected $hubspot_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=FALSE)
     * @Groups({"api", "detail", "simple"})
     * @Expose
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=8000, nullable=TRUE)
     * @Groups({"api", "detail", "simple"})
     * @Expose
     */
    protected $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=TRUE)
     * @Groups({"api", "detail", "simple"})
     * @Expose
     */
    protected $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=TRUE)
     * @Groups({"api", "detail", "simple"})
     * @Expose
     */
    protected $address2;

    /**
     * @ORM\Column(type="string", length=255, nullable=TRUE)
     * @Groups({"api", "detail", "simple"})
     * @Expose
     */
    protected $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=TRUE)
     * @Groups({"api", "detail", "simple"})
     * @Expose
     */
    protected $state;

    /**
     * @ORM\Column(type="string", length=255, nullable=TRUE)
     * @Groups({"api", "detail", "simple"})
     * @Expose
     */
    protected $zip;

    /**
     * @ORM\Column(type="string", length=255, nullable=TRUE)
     * @Groups({"api", "detail", "simple", "typeahead"})
     * @Expose
     */
    protected $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=TRUE)
     * @Groups({"api", "detail", "simple"})
     * @Expose
     */
    protected $website;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Entity\OrganizationComment", mappedBy="organization", cascade={"remove"})
     */
    protected $organization_comments;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Entity\Contact", mappedBy="organization", cascade={"remove"})
     */
    protected $contacts;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Entity\Revenue", mappedBy="organization", cascade={"remove"})
     */
    protected $revenues;

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Organization
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
     * @return Organization
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
     * Set hubspotId
     *
     * @param integer $hubspotId
     *
     * @return Organization
     */
    public function setHubspotId($hubspotId)
    {
        $this->hubspot_id = $hubspotId;

        return $this;
    }

    /**
     * Get hubspotId
     *
     * @return integer
     */
    public function getHubspotId()
    {
        return $this->hubspot_id;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Organization
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Organization
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
     * @return Organization
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
     * @return Organization
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
     * @return Organization
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
     * @return Organization
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
     * Set phone
     *
     * @param string $phone
     *
     * @return Organization
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
     * Set website
     *
     * @param string $website
     *
     * @return Organization
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    public function getComments()
    {

    }


    /**
     * Set account
     *
     * @param \App\Entity\Account $account
     *
     * @return Organization
     */
    public function setAccount(\Tavro\Entity\Account $account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return \Tavro\Entity\Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Add organizationComment
     *
     * @param \App\Entity\OrganizationComment $organizationComment
     *
     * @return Organization
     */
    public function addOrganizationComment(\Tavro\Entity\OrganizationComment $organizationComment)
    {
        $this->organization_comments[] = $organizationComment;

        return $this;
    }

    /**
     * Remove organizationComment
     *
     * @param \App\Entity\OrganizationComment $organizationComment
     */
    public function removeOrganizationComment(\Tavro\Entity\OrganizationComment $organizationComment)
    {
        $this->organization_comments->removeElement($organizationComment);
    }

    /**
     * Get organizationComments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrganizationComments()
    {
        return $this->organization_comments;
    }

    /**
     * Add revenue
     *
     * @param \App\Entity\Revenue $revenue
     *
     * @return Organization
     */
    public function addRevenue(\Tavro\Entity\Revenue $revenue)
    {
        $this->revenues[] = $revenue;

        return $this;
    }

    /**
     * Remove revenue
     *
     * @param \App\Entity\Revenue $revenue
     */
    public function removeRevenue(\Tavro\Entity\Revenue $revenue)
    {
        $this->revenues->removeElement($revenue);
    }

    /**
     * Get revenues
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRevenues()
    {
        return $this->revenues;
    }

    /**
     * Add contact
     *
     * @param \App\Entity\Contact $contact
     *
     * @return Organization
     */
    public function addContact(\Tavro\Entity\Contact $contact)
    {
        $this->contacts[] = $contact;

        return $this;
    }

    /**
     * Remove contact
     *
     * @param \App\Entity\Contact $contact
     */
    public function removeContact(\Tavro\Entity\Contact $contact)
    {
        $this->contacts->removeElement($contact);
    }

    /**
     * Get contacts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContacts()
    {
        return $this->contacts;
    }
}

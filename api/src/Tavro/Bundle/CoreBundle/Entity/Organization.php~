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
 * @ORM\Entity(repositoryClass="Tavro\Bundle\CoreBundle\Repository\OrganizationRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="tavro_organization")
 *
 */
class Organization extends AccountEntity implements AccountEntityInterface
{
    /**
     * @ORM\Column(type="integer", length=255, unique=TRUE, nullable=TRUE)
     * @Groups({"api", "detail", "simple"})
     */
    protected $hubspot_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=FALSE)
     * @Groups({"api", "detail", "simple"})
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=8000, nullable=TRUE)
     * @Groups({"api", "detail", "simple"})
     */
    protected $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=TRUE)
     * @Groups({"api", "detail", "simple"})
     */
    protected $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=TRUE)
     * @Groups({"api", "detail", "simple"})
     */
    protected $address2;

    /**
     * @ORM\Column(type="string", length=255, nullable=TRUE)
     * @Groups({"api", "detail", "simple"})
     */
    protected $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=TRUE)
     * @Groups({"api", "detail", "simple"})
     */
    protected $state;

    /**
     * @ORM\Column(type="string", length=255, nullable=TRUE)
     * @Groups({"api", "detail", "simple"})
     */
    protected $zip;

    /**
     * @ORM\Column(type="string", length=255, nullable=TRUE)
     * @Groups({"api", "detail", "simple", "typeahead"})
     */
    protected $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=TRUE)
     * @Groups({"api", "detail", "simple"})
     */
    protected $website;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\Node", mappedBy="organization", cascade={"remove"})
     */
    protected $nodes;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\Variable", mappedBy="organization", cascade={"remove"})
     */
    protected $variables;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\Tag", mappedBy="organization")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    protected $tags;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\Service", mappedBy="organization", cascade={"remove"})
     * @Groups({"detail"})
     * @MaxDepth(3)
     */
    protected $services;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\Expense", mappedBy="organization", cascade={"remove"})
     * @Groups({"detail"})
     * @MaxDepth(3)
     */
    protected $expenses;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\Product", mappedBy="organization", cascade={"remove"})
     * @Groups({"detail"})
     * @MaxDepth(3)
     */
    protected $products;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\Revenue", mappedBy="organization", cascade={"remove"})
     * @Groups({"detail"})
     * @MaxDepth(3)
     */
    protected $revenues;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\Contact", mappedBy="organization", cascade={"remove"})
     * @Groups({"detail"})
     * @MaxDepth(3)
     */
    protected $contacts;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\FundingRound", mappedBy="organization", cascade={"remove"})
     * @Groups({"detail"})
     * @MaxDepth(3)
     */
    protected $funding_rounds;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\FundingRoundShareholder", mappedBy="organization", cascade={"remove"})
     */
    protected $funding_round_shareholders;


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
     * Add node
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Node $node
     *
     * @return Organization
     */
    public function addNode(\Tavro\Bundle\CoreBundle\Entity\Node $node)
    {
        $this->nodes[] = $node;

        return $this;
    }

    /**
     * Remove node
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Node $node
     */
    public function removeNode(\Tavro\Bundle\CoreBundle\Entity\Node $node)
    {
        $this->nodes->removeElement($node);
    }

    /**
     * Get nodes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNodes()
    {
        return $this->nodes;
    }

    /**
     * Add variable
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Variable $variable
     *
     * @return Organization
     */
    public function addVariable(\Tavro\Bundle\CoreBundle\Entity\Variable $variable)
    {
        $this->variables[] = $variable;

        return $this;
    }

    /**
     * Remove variable
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Variable $variable
     */
    public function removeVariable(\Tavro\Bundle\CoreBundle\Entity\Variable $variable)
    {
        $this->variables->removeElement($variable);
    }

    /**
     * Get variables
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * Add tag
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Tag $tag
     *
     * @return Organization
     */
    public function addTag(\Tavro\Bundle\CoreBundle\Entity\Tag $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove tag
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Tag $tag
     */
    public function removeTag(\Tavro\Bundle\CoreBundle\Entity\Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Add service
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Service $service
     *
     * @return Organization
     */
    public function addService(\Tavro\Bundle\CoreBundle\Entity\Service $service)
    {
        $this->services[] = $service;

        return $this;
    }

    /**
     * Remove service
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Service $service
     */
    public function removeService(\Tavro\Bundle\CoreBundle\Entity\Service $service)
    {
        $this->services->removeElement($service);
    }

    /**
     * Get services
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * Add expense
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Expense $expense
     *
     * @return Organization
     */
    public function addExpense(\Tavro\Bundle\CoreBundle\Entity\Expense $expense)
    {
        $this->expenses[] = $expense;

        return $this;
    }

    /**
     * Remove expense
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Expense $expense
     */
    public function removeExpense(\Tavro\Bundle\CoreBundle\Entity\Expense $expense)
    {
        $this->expenses->removeElement($expense);
    }

    /**
     * Get expenses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExpenses()
    {
        return $this->expenses;
    }

    /**
     * Add product
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Product $product
     *
     * @return Organization
     */
    public function addProduct(\Tavro\Bundle\CoreBundle\Entity\Product $product)
    {
        $this->products[] = $product;

        return $this;
    }

    /**
     * Remove product
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Product $product
     */
    public function removeProduct(\Tavro\Bundle\CoreBundle\Entity\Product $product)
    {
        $this->products->removeElement($product);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Add revenue
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Revenue $revenue
     *
     * @return Organization
     */
    public function addRevenue(\Tavro\Bundle\CoreBundle\Entity\Revenue $revenue)
    {
        $this->revenues[] = $revenue;

        return $this;
    }

    /**
     * Remove revenue
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Revenue $revenue
     */
    public function removeRevenue(\Tavro\Bundle\CoreBundle\Entity\Revenue $revenue)
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
     * @param \Tavro\Bundle\CoreBundle\Entity\Contact $contact
     *
     * @return Organization
     */
    public function addContact(\Tavro\Bundle\CoreBundle\Entity\Contact $contact)
    {
        $this->contacts[] = $contact;

        return $this;
    }

    /**
     * Remove contact
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Contact $contact
     */
    public function removeContact(\Tavro\Bundle\CoreBundle\Entity\Contact $contact)
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

    /**
     * Add fundingRound
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\FundingRound $fundingRound
     *
     * @return Organization
     */
    public function addFundingRound(\Tavro\Bundle\CoreBundle\Entity\FundingRound $fundingRound)
    {
        $this->funding_rounds[] = $fundingRound;

        return $this;
    }

    /**
     * Remove fundingRound
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\FundingRound $fundingRound
     */
    public function removeFundingRound(\Tavro\Bundle\CoreBundle\Entity\FundingRound $fundingRound)
    {
        $this->funding_rounds->removeElement($fundingRound);
    }

    /**
     * Get fundingRounds
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFundingRounds()
    {
        return $this->funding_rounds;
    }

    /**
     * Add fundingRoundShareholder
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\FundingRoundShareholder $fundingRoundShareholder
     *
     * @return Organization
     */
    public function addFundingRoundShareholder(\Tavro\Bundle\CoreBundle\Entity\FundingRoundShareholder $fundingRoundShareholder)
    {
        $this->funding_round_shareholders[] = $fundingRoundShareholder;

        return $this;
    }

    /**
     * Remove fundingRoundShareholder
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\FundingRoundShareholder $fundingRoundShareholder
     */
    public function removeFundingRoundShareholder(\Tavro\Bundle\CoreBundle\Entity\FundingRoundShareholder $fundingRoundShareholder)
    {
        $this->funding_round_shareholders->removeElement($fundingRoundShareholder);
    }

    /**
     * Get fundingRoundShareholders
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFundingRoundShareholders()
    {
        return $this->funding_round_shareholders;
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
}

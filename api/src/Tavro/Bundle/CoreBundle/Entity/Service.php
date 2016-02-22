<?php
namespace Tavro\Bundle\CoreBundle\Entity;

use Tavro\Bundle\CoreBundle\Model\Api\ApiEntityInterface;
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

use Tavro\Bundle\CoreBundle\Model\Api\ApiEntity;
use Tavro\Bundle\CoreBundle\Model\EntityInterface;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="tavro_service")
 *
 */
class Service extends ApiEntity
{
    /**
     * @ORM\Column(type="string", length=8000, nullable=true)
     * @Groups({"api", "tavro", "simple"})
     */
    protected $body;

    /**
     * @ORM\Column(type="float", nullable=false, options={"default" = 0})
     * @Groups({"api", "tavro", "simple"})
     */
    protected $price;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Assert\Choice(
     *      choices = {"hour", "unit", "retainer"},
     *      message = "Choose a valid service type."
     * )
     * @Groups({"api", "tavro", "simple"})
     */
    protected $type;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\ServiceCategory", inversedBy="services")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=false)
     * @Groups({"api", "tavro", "simple"})
     * @MaxDepth(3)
     */
    protected $category;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Organization", inversedBy="services")
     * @ORM\JoinColumn(name="organization_id", referencedColumnName="id", nullable=false)
     * @Groups({"api", "tavro", "simple"})
     * @MaxDepth(3)
     */
    protected $organization;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\RevenueService", mappedBy="service", cascade={"remove"})
     */
    protected $revenue_services;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->status = 1;
        $now = new \DateTime();
        $tz = new \DateTimeZone('America/New_York');
        $now->setTimezone($tz);
        $this->create_date = $now;
    }

    public function __toString()
    {
        return $this->title;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return Service
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
     * Set organization
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Organization $organization
     * @return Service
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


    /**
     * Set category
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\ServiceCategory $category
     *
     * @return Service
     */
    public function setCategory(\Tavro\Bundle\CoreBundle\Entity\ServiceCategory $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\ServiceCategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add revenueService
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\RevenueService $revenueService
     *
     * @return Service
     */
    public function addRevenueService(\Tavro\Bundle\CoreBundle\Entity\RevenueService $revenueService)
    {
        $this->revenue_services[] = $revenueService;

        return $this;
    }

    /**
     * Remove revenueService
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\RevenueService $revenueService
     */
    public function removeRevenueService(\Tavro\Bundle\CoreBundle\Entity\RevenueService $revenueService)
    {
        $this->revenue_services->removeElement($revenueService);
    }

    /**
     * Get revenueServices
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRevenueServices()
    {
        return $this->revenue_services;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Service
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Service
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}

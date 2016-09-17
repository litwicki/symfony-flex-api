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
 * @ORM\Entity(repositoryClass="Tavro\Bundle\CoreBundle\Repository\ServiceRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="tavro_service")
 *
 */
class Service extends AccountEntity implements AccountEntityInterface
{

    /**
     * @ORM\Column(type="string", length=255, nullable=FALSE)
     * @Groups({"api", "tavro", "simple"})
     */
    protected $title;

    /**
     * @ORM\Column(type="float", nullable=FALSE, options={"default" = 0})
     * @Groups({"api", "tavro", "simple"})
     */
    protected $price;

    /**
     * @ORM\Column(type="string", nullable=FALSE)
     * @Assert\Choice(
     *      choices = {"hour", "unit", "retainer"},
     *      message = "Choose a valid service type."
     * )
     * @Groups({"api", "tavro", "simple"})
     */
    protected $type;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\ServiceCategory", inversedBy="services")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "tavro", "simple"})
     * @MaxDepth(3)
     */
    protected $category;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\RevenueService", mappedBy="service", cascade={"remove"})
     */
    protected $revenue_services;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\ServiceImage", mappedBy="service", cascade={"remove"})
     */
    protected $service_images;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->status = self::STATUS_ENABLED;
    }

    public function __toString()
    {
        return $this->body;
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

    /**
     * Add serviceImage
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\ServiceImage $serviceImage
     *
     * @return Service
     */
    public function addServiceImage(\Tavro\Bundle\CoreBundle\Entity\ServiceImage $serviceImage)
    {
        $this->service_images[] = $serviceImage;

        return $this;
    }

    /**
     * Remove serviceImage
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\ServiceImage $serviceImage
     */
    public function removeServiceImage(\Tavro\Bundle\CoreBundle\Entity\ServiceImage $serviceImage)
    {
        $this->service_images->removeElement($serviceImage);
    }

    /**
     * Get serviceImages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServiceImages()
    {
        return $this->service_images;
    }

    /**
     * Set body
     *
     * @param string $body
     *
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
     * Set title
     *
     * @param string $title
     *
     * @return Service
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
}

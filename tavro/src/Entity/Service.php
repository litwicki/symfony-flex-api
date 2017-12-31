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
 * @ORM\Entity(repositoryClass="Tavro\Repository\AccountEntity\ServiceRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="tavro_service")
 * @ExclusionPolicy("all")
 *
 */
class Service extends Entity implements AccountEntityInterface
{

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\Account", inversedBy="services")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"detail"})
     * @MaxDepth(1)
     * @Expose
     */
    protected $account;

    /**
     * @ORM\Column(type="string", length=255, nullable=FALSE)
     * @Groups({"api", "detail", "simple"})
     * @Expose
     */
    protected $name;

    /**
     * @ORM\Column(type="float", nullable=FALSE, options={"default" = 0})
     * @Groups({"api", "detail", "simple"})
     * @Assert\GreaterThan(
     *     value = 0,
     *     message = "Price must be greater than 0"
     * )
     * @Expose
     */
    protected $price;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\ServiceCategory", inversedBy="services")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "detail", "simple"})
     * @MaxDepth(3)
     * @Expose
     */
    protected $category;

    /**
     * @Type("integer")
     * @ORM\Column(type="integer", nullable=TRUE, unique=TRUE)
     * @Groups({"api", "detail", "simple"})
     * @Expose
     */
    protected $qbo_id;

    /**
     * @ORM\Column(type="float", nullable=TRUE, options={"default" = 0})
     * @Groups({"api", "detail", "simple"})
     * @Expose
     */
    protected $discount_amount;

    /**
     * @ORM\Column(type="float", nullable=TRUE, options={"default" = 0})
     * @Groups({"api", "detail", "simple"})
     * @Expose
     */
    protected $discount_percent;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Entity\RevenueService", mappedBy="service", cascade={"remove"})
     */
    protected $revenue_services;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Entity\ServiceImage", mappedBy="service", cascade={"remove"})
     * @Expose
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
     * @param \App\Entity\ServiceCategory $category
     *
     * @return Service
     */
    public function setCategory(\Tavro\Entity\ServiceCategory $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Tavro\Entity\ServiceCategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add revenueService
     *
     * @param \App\Entity\RevenueService $revenueService
     *
     * @return Service
     */
    public function addRevenueService(\Tavro\Entity\RevenueService $revenueService)
    {
        $this->revenue_services[] = $revenueService;

        return $this;
    }

    /**
     * Remove revenueService
     *
     * @param \App\Entity\RevenueService $revenueService
     */
    public function removeRevenueService(\Tavro\Entity\RevenueService $revenueService)
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
     * Add serviceImage
     *
     * @param \App\Entity\ServiceImage $serviceImage
     *
     * @return Service
     */
    public function addServiceImage(\Tavro\Entity\ServiceImage $serviceImage)
    {
        $this->service_images[] = $serviceImage;

        return $this;
    }

    /**
     * Remove serviceImage
     *
     * @param \App\Entity\ServiceImage $serviceImage
     */
    public function removeServiceImage(\Tavro\Entity\ServiceImage $serviceImage)
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
     * Set name
     *
     * @param string $name
     *
     * @return Service
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
     * Set account
     *
     * @param \App\Entity\Account $account
     *
     * @return Service
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
     * Set qboId
     *
     * @param integer $qboId
     *
     * @return Service
     */
    public function setQboId($qboId)
    {
        $this->qbo_id = $qboId;

        return $this;
    }

    /**
     * Get qboId
     *
     * @return integer
     */
    public function getQboId()
    {
        return $this->qbo_id;
    }

    /**
     * Set discountAmount
     *
     * @param float $discountAmount
     *
     * @return Service
     */
    public function setDiscountAmount($discountAmount)
    {
        $this->discount_amount = $discountAmount;

        return $this;
    }

    /**
     * Get discountAmount
     *
     * @return float
     */
    public function getDiscountAmount()
    {
        return $this->discount_amount;
    }

    /**
     * Set discountPercent
     *
     * @param float $discountPercent
     *
     * @return Service
     */
    public function setDiscountPercent($discountPercent)
    {
        $this->discount_percent = $discountPercent;

        return $this;
    }

    /**
     * Get discountPercent
     *
     * @return float
     */
    public function getDiscountPercent()
    {
        return $this->discount_percent;
    }
}

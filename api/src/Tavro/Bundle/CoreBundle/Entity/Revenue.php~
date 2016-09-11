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

use Tavro\Bundle\CoreBundle\Model\OrganizationEntity;
use Tavro\Bundle\CoreBundle\Model\OrganizationEntityInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Bundle\CoreBundle\Doctrine\Repository\Entity\RevenueRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="tavro_revenue")
 *
 */
class Revenue extends OrganizationEntity implements OrganizationEntityInterface
{

    /**
     * @ORM\Column(type="string", nullable=FALSE)
     * @Assert\Choice(
     *      choices = { "sale", "service", "other"},
     *      message = "Choose a valid Revenue type."
     * )
     * @Groups({"api", "tavro", "simple"})
     */
    protected $type;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\RevenueCategory", inversedBy="revenues")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "tavro", "simple"})
     * @MaxDepth(1)
     */
    protected $category;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\User", inversedBy="revenues")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "tavro", "simple"})
     * @MaxDepth(1)
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Customer", inversedBy="revenues")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id", nullable=TRUE)
     * @Groups({"api", "tavro", "simple"})
     * @MaxDepth(1)
     */
    protected $customer;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\RevenueComment", mappedBy="revenue", cascade={"remove"})
     */
    protected $revenue_comments;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\RevenueService", mappedBy="revenue", cascade={"remove"})
     */
    protected $revenue_services;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\RevenueProduct", mappedBy="revenue", cascade={"remove"})
     */
    protected $revenue_products;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\RevenueTag", mappedBy="revenue", cascade={"remove"})
     */
    protected $revenue_tags;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->revenue_tags = new \Doctrine\Common\Collections\ArrayCollection();
        $this->revenue_comments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->revenue_services = new \Doctrine\Common\Collections\ArrayCollection();
        $this->revenue_products = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set type
     *
     * @param string $type
     * @return Revenue
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
     * Set body
     *
     * @param string $body
     * @return Revenue
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
     * @return Revenue
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
     * Add revenue_comments
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\RevenueComment $revenueComments
     * @return Revenue
     */
    public function addRevenueComment(\Tavro\Bundle\CoreBundle\Entity\RevenueComment $revenueComments)
    {
        $this->revenue_comments[] = $revenueComments;

        return $this;
    }

    /**
     * Remove revenue_comments
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\RevenueComment $revenueComments
     */
    public function removeRevenueComment(\Tavro\Bundle\CoreBundle\Entity\RevenueComment $revenueComments)
    {
        $this->revenue_comments->removeElement($revenueComments);
    }

    /**
     * Get revenue_comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRevenueComments()
    {
        return $this->revenue_comments;
    }

    /**
     * @VirtualProperty
     * @SerializedName("comments")
     * @Groups({"api", "tavro","detail"})
     * @MaxDepth(1)
     */
    public function getComments()
    {
        $comments = new \Doctrine\Common\Collections\ArrayCollection();
        foreach($this->revenue_comments as $revenueComment) {
            $comments->add($revenueComment->getComment());
        }
        return $comments;
    }
    /**
     * @VirtualProperty
     * @SerializedName("comment_count")
     * @Groups({"api", "tavro", "simple"})
     * @return int
     */
    public function getCommentCount()
    {
        return $this->revenue_comments->count();
    }

    /**
     * @VirtualProperty
     * @SerializedName("tags")
     * @Groups({"api", "tavro","detail"})
     * @MaxDepth(1)
     */
    public function getTags()
    {
        $tags = new \Doctrine\Common\Collections\ArrayCollection();
        foreach($this->revenue_tags as $revenueTag) {
            $tags->add($revenueTag->getTag());
        }
        return $tags;
    }

    /**
     * Add revenue_tags
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\RevenueTag $revenueTags
     * @return Revenue
     */
    public function addRevenueTag(\Tavro\Bundle\CoreBundle\Entity\RevenueTag $revenueTags)
    {
        $this->revenue_tags[] = $revenueTags;

        return $this;
    }

    /**
     * Remove revenue_tags
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\RevenueTag $revenueTags
     */
    public function removeRevenueTag(\Tavro\Bundle\CoreBundle\Entity\RevenueTag $revenueTags)
    {
        $this->revenue_tags->removeElement($revenueTags);
    }

    /**
     * Get revenue_tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRevenueTags()
    {
        return $this->revenue_tags;
    }


    /**
     * Set category
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\RevenueCategory $category
     *
     * @return Revenue
     */
    public function setCategory(\Tavro\Bundle\CoreBundle\Entity\RevenueCategory $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\RevenueCategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set customer
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Customer $customer
     *
     * @return Revenue
     */
    public function setCustomer(\Tavro\Bundle\CoreBundle\Entity\Customer $customer)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Add revenueService
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\RevenueService $revenueService
     *
     * @return Revenue
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
     * Add revenueProduct
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\RevenueProduct $revenueProduct
     *
     * @return Revenue
     */
    public function addRevenueProduct(\Tavro\Bundle\CoreBundle\Entity\RevenueProduct $revenueProduct)
    {
        $this->revenue_products[] = $revenueProduct;

        return $this;
    }

    /**
     * Remove revenueProduct
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\RevenueProduct $revenueProduct
     */
    public function removeRevenueProduct(\Tavro\Bundle\CoreBundle\Entity\RevenueProduct $revenueProduct)
    {
        $this->revenue_products->removeElement($revenueProduct);
    }

    /**
     * Get revenueProducts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRevenueProducts()
    {
        return $this->revenue_products;
    }

    /**
     * @VirtualProperty
     * @SerializedName("services")
     * @Groups({"api", "tavro","detail"})
     * @MaxDepth(2)
     */
    public function getServices()
    {
        $items = new \Doctrine\Common\Collections\ArrayCollection();
        foreach($this->revenue_services as $entity) {
            $items->add($entity->getService());
        }
        return $items;
    }

    /**
     * @VirtualProperty
     * @SerializedName("products")
     * @Groups({"api", "tavro","detail"})
     * @MaxDepth(2)
     */
    public function getProducts()
    {
        $items = new \Doctrine\Common\Collections\ArrayCollection();
        foreach($this->revenue_products as $entity) {
            $items->add($entity->getProduct());
        }
        return $items;
    }
}

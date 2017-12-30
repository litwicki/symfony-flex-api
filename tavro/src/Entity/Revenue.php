<?php
namespace Tavro\Entity;

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

use Tavro\Model\Entity\Entity;
use Tavro\Model\EntityInterface\EntityInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Repository\AccountEntity\RevenueRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="tavro_revenue")
 * @ExclusionPolicy("all")
 *
 */
class Revenue extends Entity implements EntityInterface
{

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\Account", inversedBy="revenues")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "detail", "simple"})
     * @MaxDepth(1)
     * @Expose
     */
    protected $account;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\Organization", inversedBy="revenues")
     * @ORM\JoinColumn(name="organization_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "detail", "simple"})
     * @MaxDepth(1)
     * @Expose
     */
    protected $organization;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\RevenueCategory", inversedBy="revenues")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "detail", "simple"})
     * @MaxDepth(1)
     * @Expose
     */
    protected $category;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\User", inversedBy="revenues")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "detail", "simple"})
     * @MaxDepth(1)
     * @Expose
     */
    protected $user;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Entity\RevenueComment", mappedBy="revenue", cascade={"remove"})
     */
    protected $revenue_comments;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Entity\RevenueService", mappedBy="revenue", cascade={"remove"})
     */
    protected $revenue_services;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Entity\RevenueProduct", mappedBy="revenue", cascade={"remove"})
     */
    protected $revenue_products;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Entity\RevenueTag", mappedBy="revenue", cascade={"remove"})
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
    }

    public function __toString()
    {
        return $this->body;
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
     * @param \Tavro\Entity\User $user
     * @return Revenue
     */
    public function setUser(\Tavro\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Tavro\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add revenue_comments
     *
     * @param \Tavro\Entity\RevenueComment $revenueComments
     * @return Revenue
     */
    public function addRevenueComment(\Tavro\Entity\RevenueComment $revenueComments)
    {
        $this->revenue_comments[] = $revenueComments;

        return $this;
    }

    /**
     * Remove revenue_comments
     *
     * @param \Tavro\Entity\RevenueComment $revenueComments
     */
    public function removeRevenueComment(\Tavro\Entity\RevenueComment $revenueComments)
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
     * @Groups({"api", "detail","detail"})
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
     * @Groups({"api", "detail", "simple"})
     * @return int
     */
    public function getCommentCount()
    {
        return $this->revenue_comments->count();
    }

    /**
     * @VirtualProperty
     * @SerializedName("tags")
     * @Groups({"api", "detail","detail"})
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
     * @param \Tavro\Entity\RevenueTag $revenueTags
     * @return Revenue
     */
    public function addRevenueTag(\Tavro\Entity\RevenueTag $revenueTags)
    {
        $this->revenue_tags[] = $revenueTags;

        return $this;
    }

    /**
     * Remove revenue_tags
     *
     * @param \Tavro\Entity\RevenueTag $revenueTags
     */
    public function removeRevenueTag(\Tavro\Entity\RevenueTag $revenueTags)
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
     * @param \Tavro\Entity\RevenueCategory $category
     *
     * @return Revenue
     */
    public function setCategory(\Tavro\Entity\RevenueCategory $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Tavro\Entity\RevenueCategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add revenueService
     *
     * @param \Tavro\Entity\RevenueService $revenueService
     *
     * @return Revenue
     */
    public function addRevenueService(\Tavro\Entity\RevenueService $revenueService)
    {
        $this->revenue_services[] = $revenueService;

        return $this;
    }

    /**
     * Remove revenueService
     *
     * @param \Tavro\Entity\RevenueService $revenueService
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
     * Add revenueProduct
     *
     * @param \Tavro\Entity\RevenueProduct $revenueProduct
     *
     * @return Revenue
     */
    public function addRevenueProduct(\Tavro\Entity\RevenueProduct $revenueProduct)
    {
        $this->revenue_products[] = $revenueProduct;

        return $this;
    }

    /**
     * Remove revenueProduct
     *
     * @param \Tavro\Entity\RevenueProduct $revenueProduct
     */
    public function removeRevenueProduct(\Tavro\Entity\RevenueProduct $revenueProduct)
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
     * @Groups({"api", "detail","detail"})
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
     * @Groups({"api", "detail", "simple"})
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

    /**
     * Set organization
     *
     * @param \Tavro\Entity\Organization $organization
     *
     * @return Revenue
     */
    public function setOrganization(\Tavro\Entity\Organization $organization)
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * Get organization
     *
     * @return \Tavro\Entity\Organization
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * Calculate the total value of this Revenue Item.
     *
     * @VirtualProperty
     * @SerializedName("total")
     * @Groups({"api", "detail", "simple"})
     *
     */
    public function getTotal()
    {
        $products = $this->getProducts();
        $services = $this->getServices();
        $total = 0;

        if(!empty($products)) {
            foreach($products as $product) {
                $total += $product->getPrice();
            }
        }

        if(!empty($services)) {
            foreach($services as $service) {
                $total += $service->getPrice();
            }
        }

        return $total;
    }


    /**
     * Set account
     *
     * @param \Tavro\Entity\Account $account
     *
     * @return Revenue
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
}

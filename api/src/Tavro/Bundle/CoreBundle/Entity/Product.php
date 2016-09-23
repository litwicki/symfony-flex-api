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

use Tavro\Bundle\CoreBundle\Model\Entity;
use Tavro\Bundle\CoreBundle\Model\AccountEntityInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Bundle\CoreBundle\Repository\ProductRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="tavro_product")
 *
 */
class Product extends Entity implements AccountEntityInterface
{

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Account", inversedBy="products")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"detail"})
     * @MaxDepth(1)
     */
    protected $account;

    /**
     * @ORM\Column(type="string", length=255, nullable=FALSE)
     * @Groups({"api", "detail", "simple"})
     */
    protected $name;

    /**
     * @ORM\Column(type="float", nullable=FALSE, options={"default" = 0})
     * @Groups({"api", "detail", "simple"})
     */
    protected $price;

    /**
     * @ORM\Column(type="float", nullable=TRUE, options={"default" = 0})
     * @Groups({"api", "detail", "simple"})
     */
    protected $cost;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\ProductCategory", inversedBy="products")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "detail", "simple"})
     * @MaxDepth(3)
     */
    protected $category;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\ProductImage", mappedBy="product", cascade={"remove"})
     */
    protected $product_images;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\RevenueProduct", mappedBy="product", cascade={"remove"})
     */
    protected $revenue_products;

    public function __toString()
    {
        return $this->name;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return Product
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
     * @return Product
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
     * Set category
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\ProductCategory $category
     *
     * @return Product
     */
    public function setCategory(\Tavro\Bundle\CoreBundle\Entity\ProductCategory $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\ProductCategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add productImage
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\ProductImage $productImage
     *
     * @return Product
     */
    public function addProductImage(\Tavro\Bundle\CoreBundle\Entity\ProductImage $productImage)
    {
        $this->product_images[] = $productImage;

        return $this;
    }

    /**
     * Remove productImage
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\ProductImage $productImage
     */
    public function removeProductImage(\Tavro\Bundle\CoreBundle\Entity\ProductImage $productImage)
    {
        $this->product_images->removeElement($productImage);
    }

    /**
     * Get productImages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProductImages()
    {
        return $this->product_images;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Product
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
     * Set cost
     *
     * @param float $cost
     *
     * @return Product
     */
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * Get cost
     *
     * @return float
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Add revenueProduct
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\RevenueProduct $revenueProduct
     *
     * @return Product
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
     * Set account
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Account $account
     *
     * @return Product
     */
    public function setAccount(\Tavro\Bundle\CoreBundle\Entity\Account $account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\Account
     */
    public function getAccount()
    {
        return $this->account;
    }
}

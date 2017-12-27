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

use Tavro\Bundle\CoreBundle\Model\Entity\Entity;
use Tavro\Bundle\CoreBundle\Model\EntityInterface\AccountEntityInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Bundle\CoreBundle\Repository\AccountEntity\ProductCategoryRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="tavro_product_category")
 * @ExclusionPolicy("all")
 *
 */
class ProductCategory extends Entity implements AccountEntityInterface
{

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Account", inversedBy="product_categories")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"detail"})
     * @MaxDepth(1)
     * @Expose
     */
    protected $account;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\Product", mappedBy="category")
     * @ORM\OrderBy({"id" = "DESC"})
     * @Expose
     */
    protected $products;

    /**
     * Set body
     *
     * @param string $body
     *
     * @return ProductCategory
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
     * Set account
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Account $account
     *
     * @return ProductCategory
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

    /**
     * Add product
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Product $product
     *
     * @return ProductCategory
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
}

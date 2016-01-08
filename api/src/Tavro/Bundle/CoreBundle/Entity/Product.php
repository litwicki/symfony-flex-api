<?php
namespace Tavro\Bundle\CoreBundle\Entity;

use Tavro\Bundle\CoreBundle\Model\ApiEntityInterface;
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

use Tavro\Bundle\CoreBundle\Model\ApiEntity;
use Tavro\Bundle\CoreBundle\Model\EntityInterface;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="tavro_product")
 *
 */
class Product extends ApiEntity
{
    /**
     * @ORM\Column(type="string", length=8000, nullable=true)
     * @Groups({"api", "tavro", "summary"})
     */
    protected $body;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\ProductCategory", inversedBy="products")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=false)
     * @Groups({"api", "tavro", "summary"})
     * @MaxDepth(3)
     */
    protected $category;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Organization", inversedBy="products")
     * @ORM\JoinColumn(name="organization_id", referencedColumnName="id", nullable=false)
     * @Groups({"api", "tavro", "summary"})
     * @MaxDepth(3)
     */
    protected $organization;

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
     * Set organization
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Organization $organization
     * @return Product
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
}

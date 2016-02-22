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

use Tavro\Bundle\CoreBundle\Model\Entity;
use Tavro\Bundle\CoreBundle\Model\EntityInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Bundle\CoreBundle\Repository\ProductCategoryRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="tavro_product_category")
 *
 */
class ProductCategory extends Entity
{
    /**
     * @ORM\Column(type="string", length=500, nullable=false)
     * @Groups({"api", "tavro", "simple"})
     */
    protected $body;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Organization", inversedBy="product_categories")
     * @ORM\JoinColumn(name="organization_id", referencedColumnName="id", nullable=false)
     * @Groups({"api", "tavro", "simple"})
     * @MaxDepth(3)
     */
    protected $organization;

    /**
     * Set body
     *
     * @param string $body
     * @return Tag
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
     * @return Expense
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


}

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
 * @ORM\Entity(repositoryClass="Tavro\Bundle\CoreBundle\Repository\AccountEntity\RevenueCategoryRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="tavro_revenue_category")
 * @ExclusionPolicy("all")
 *
 */
class RevenueCategory extends Entity implements AccountEntityInterface
{

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Account", inversedBy="revenue_categories")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"detail"})
     * @MaxDepth(1)
     */
    protected $account;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Bundle\CoreBundle\Entity\Revenue", mappedBy="category")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    protected $revenues;

    /**
     * Set body
     *
     * @param string $body
     *
     * @return RevenueCategory
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
     * @return RevenueCategory
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
     * Add revenue
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Revenue $revenue
     *
     * @return RevenueCategory
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
}

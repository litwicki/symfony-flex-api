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
 * @ORM\Entity(repositoryClass="Tavro\Repository\AccountEntity\RevenueCategoryRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="tavro_revenue_category")
 * @ExclusionPolicy("all")
 *
 */
class RevenueCategory extends Entity implements AccountEntityInterface
{

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\Account", inversedBy="revenue_categories")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"detail"})
     * @MaxDepth(1)
     */
    protected $account;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Entity\Revenue", mappedBy="category")
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
     * @param \App\Entity\Account $account
     *
     * @return RevenueCategory
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
     * Add revenue
     *
     * @param \App\Entity\Revenue $revenue
     *
     * @return RevenueCategory
     */
    public function addRevenue(\Tavro\Entity\Revenue $revenue)
    {
        $this->revenues[] = $revenue;

        return $this;
    }

    /**
     * Remove revenue
     *
     * @param \App\Entity\Revenue $revenue
     */
    public function removeRevenue(\Tavro\Entity\Revenue $revenue)
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

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
use Tavro\Model\EntityInterface\ForecastEntityInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Repository\Entity\ForecastRevenueRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="tavro_forecast_revenue")
 * @ExclusionPolicy("all")
 *
 */
class ForecastRevenue extends Entity implements ForecastEntityInterface
{
    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\Forecast", inversedBy="forecast_revenues")
     * @ORM\JoinColumn(name="forecast_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"detail"})
     * @MaxDepth(1)
     */
    protected $forecast;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\RevenueCategory", inversedBy="forecast_revenues")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "detail", "simple"})
     * @MaxDepth(1)
     */
    protected $category;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\User", inversedBy="forecast_revenues")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "detail", "simple"})
     * @MaxDepth(1)
     */
    protected $user;

    /**
     * @Type("datetime")
     * @ORM\Column(type="datetime", length=1000, nullable=false)
     * @Groups({"api", "detail", "simple"})
     */
    protected $revenue_date;

    /**
     * @ORM\Column(type="integer", nullable=false, options={"default" : 0})
     * @Groups({"api", "detail", "simple"})
     * @Assert\GreaterThanOrEqual(
     *     value = 0,
     *     message = "Qty must be greater than 0"
     * )
     */
    protected $qty;

    /**
     * @ORM\Column(type="integer", nullable=false, options={"default" : 0})
     * @Groups({"api", "detail", "simple"})
     * @Assert\GreaterThanOrEqual(
     *     value = 0,
     *     message = "Unit Amount must be greater than 0"
     * )
     */
    protected $unit_amount;

    /**
     * Set revenueDate
     *
     * @param \DateTime $revenueDate
     *
     * @return ForecastRevenue
     */
    public function setRevenueDate($revenueDate)
    {
        $this->revenue_date = $revenueDate;

        return $this;
    }

    /**
     * Get revenueDate
     *
     * @return \DateTime
     */
    public function getRevenueDate()
    {
        return $this->revenue_date;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return ForecastRevenue
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
     * Set category
     *
     * @param \Tavro\Entity\RevenueCategory $category
     *
     * @return ForecastRevenue
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
     * Set user
     *
     * @param \Tavro\Entity\User $user
     *
     * @return ForecastRevenue
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
     * Set amount
     *
     * @param integer $amount
     *
     * @return ForecastRevenue
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return integer
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set qty
     *
     * @param integer $qty
     *
     * @return ForecastRevenue
     */
    public function setQty($qty)
    {
        $this->qty = $qty;

        return $this;
    }

    /**
     * Get qty
     *
     * @return integer
     */
    public function getQty()
    {
        return $this->qty;
    }

    /**
     * Set unitAmount
     *
     * @param integer $unitAmount
     *
     * @return ForecastRevenue
     */
    public function setUnitAmount($unitAmount)
    {
        $this->unit_amount = $unitAmount;

        return $this;
    }

    /**
     * Get unitAmount
     *
     * @return integer
     */
    public function getUnitAmount()
    {
        return $this->unit_amount;
    }

    /**
     * Set forecast
     *
     * @param \Tavro\Entity\Forecast $forecast
     *
     * @return ForecastRevenue
     */
    public function setForecast(\Tavro\Entity\Forecast $forecast)
    {
        $this->forecast = $forecast;

        return $this;
    }

    /**
     * Get forecast
     *
     * @return \Tavro\Entity\Forecast
     */
    public function getForecast()
    {
        return $this->forecast;
    }
}

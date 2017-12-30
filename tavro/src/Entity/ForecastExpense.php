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
 * @ORM\Table(name="tavro_forecast_expense")
 * @ExclusionPolicy("all")
 *
 */
class ForecastExpense extends Entity implements ForecastEntityInterface
{
    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\Forecast", inversedBy="forecast_expenses")
     * @ORM\JoinColumn(name="forecast_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"detail"})
     * @MaxDepth(1)
     */
    protected $forecast;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\ExpenseCategory", inversedBy="forecast_expenses")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "detail", "simple"})
     * @MaxDepth(1)
     */
    protected $category;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\User", inversedBy="forecast_expenses")
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
    protected $expense_date;

    /**
     * @ORM\Column(type="integer", nullable=false, options={"default" : 0})
     * @Groups({"api", "detail", "simple"})
     * @Assert\GreaterThan(
     *     value = 0,
     *     message = "Forecast Expense Amount must be greater than 0"
     * )
     */
    protected $amount;

    /**
     * Set expenseDate
     *
     * @param \DateTime $expenseDate
     *
     * @return ForecastExpense
     */
    public function setExpenseDate($expenseDate)
    {
        $this->expense_date = $expenseDate;

        return $this;
    }

    /**
     * Get expenseDate
     *
     * @return \DateTime
     */
    public function getExpenseDate()
    {
        return $this->expense_date;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     *
     * @return ForecastExpense
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
     * Set body
     *
     * @param string $body
     *
     * @return ForecastExpense
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
     * @param \Tavro\Entity\ExpenseCategory $category
     *
     * @return ForecastExpense
     */
    public function setCategory(\Tavro\Entity\ExpenseCategory $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Tavro\Entity\ExpenseCategory
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
     * @return ForecastExpense
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
     * Set forecast
     *
     * @param \Tavro\Entity\Forecast $forecast
     *
     * @return ForecastExpense
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

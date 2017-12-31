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
 * @ORM\Entity(repositoryClass="Tavro\Repository\AccountEntity\ForecastRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="tavro_forecast")
 * @ExclusionPolicy("all")
 *
 */
class Forecast extends Entity implements AccountEntityInterface
{

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\User", inversedBy="forecasts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "detail", "simple"})
     * @MaxDepth(1)
     * @Expose
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\Account", inversedBy="forecasts")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"detail"})
     * @MaxDepth(1)
     * @Expose
     */
    protected $account;

    /**
     * @ORM\Column(type="string", length=255, nullable=FALSE)
     * @Groups({"api", "detail", "simple"})
     * @Expose
     */
    protected $title;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Entity\ForecastComment", mappedBy="forecast", cascade={"remove"})
     */
    protected $forecast_comments;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Entity\ForecastExpense", mappedBy="forecast", cascade={"remove"})
     */
    protected $forecast_expenses;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Entity\ForecastRevenue", mappedBy="forecast", cascade={"remove"})
     */
    protected $forecast_revenues;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Entity\ForecastStaffPerson", mappedBy="forecast", cascade={"remove"})
     */
    protected $forecast_staff_persons;


    /**
     * Set title
     *
     * @param string $title
     *
     * @return Forecast
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return Forecast
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
     * @return Forecast
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
     * Add forecastComment
     *
     * @param \App\Entity\ForecastComment $forecastComment
     *
     * @return Forecast
     */
    public function addForecastComment(\Tavro\Entity\ForecastComment $forecastComment)
    {
        $this->forecast_comments[] = $forecastComment;

        return $this;
    }

    /**
     * Remove forecastComment
     *
     * @param \App\Entity\ForecastComment $forecastComment
     */
    public function removeForecastComment(\Tavro\Entity\ForecastComment $forecastComment)
    {
        $this->forecast_comments->removeElement($forecastComment);
    }

    /**
     * Get forecastComments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getForecastComments()
    {
        return $this->forecast_comments;
    }

    /**
     * Set user
     *
     * @param \App\Entity\User $user
     *
     * @return Forecast
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
     * Add forecastExpense
     *
     * @param \App\Entity\ForecastExpense $forecastExpense
     *
     * @return Forecast
     */
    public function addForecastExpense(\Tavro\Entity\ForecastExpense $forecastExpense)
    {
        $this->forecast_expenses[] = $forecastExpense;

        return $this;
    }

    /**
     * Remove forecastExpense
     *
     * @param \App\Entity\ForecastExpense $forecastExpense
     */
    public function removeForecastExpense(\Tavro\Entity\ForecastExpense $forecastExpense)
    {
        $this->forecast_expenses->removeElement($forecastExpense);
    }

    /**
     * Get forecastExpenses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getForecastExpenses()
    {
        return $this->forecast_expenses;
    }

    /**
     * Add forecastRevenue
     *
     * @param \App\Entity\ForecastRevenue $forecastRevenue
     *
     * @return Forecast
     */
    public function addForecastRevenue(\Tavro\Entity\ForecastRevenue $forecastRevenue)
    {
        $this->forecast_revenues[] = $forecastRevenue;

        return $this;
    }

    /**
     * Remove forecastRevenue
     *
     * @param \App\Entity\ForecastRevenue $forecastRevenue
     */
    public function removeForecastRevenue(\Tavro\Entity\ForecastRevenue $forecastRevenue)
    {
        $this->forecast_revenues->removeElement($forecastRevenue);
    }

    /**
     * Get forecastRevenues
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getForecastRevenues()
    {
        return $this->forecast_revenues;
    }

    /**
     * Add forecastStaffPerson
     *
     * @param \App\Entity\ForecastStaffPerson $forecastStaffPerson
     *
     * @return Forecast
     */
    public function addForecastStaffPerson(\Tavro\Entity\ForecastStaffPerson $forecastStaffPerson)
    {
        $this->forecast_staff_persons[] = $forecastStaffPerson;

        return $this;
    }

    /**
     * Remove forecastStaffPerson
     *
     * @param \App\Entity\ForecastStaffPerson $forecastStaffPerson
     */
    public function removeForecastStaffPerson(\Tavro\Entity\ForecastStaffPerson $forecastStaffPerson)
    {
        $this->forecast_staff_persons->removeElement($forecastStaffPerson);
    }

    /**
     * Get forecastStaffPersons
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getForecastStaffPersons()
    {
        return $this->forecast_staff_persons;
    }
}

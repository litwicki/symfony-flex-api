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
use Tavro\Bundle\CoreBundle\Model\Entity\StaffPersonModel;
use Tavro\Bundle\CoreBundle\Model\EntityInterface\ForecastEntityInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Bundle\CoreBundle\Repository\Entity\ForecastStaffPersonRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="tavro_forecast_staff_person")
 * @ExclusionPolicy("all")
 *
 */
class ForecastStaffPerson extends StaffPersonModel implements ForecastEntityInterface
{
    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Forecast", inversedBy="forecast_staff_persons")
     * @ORM\JoinColumn(name="forecast_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"detail"})
     * @MaxDepth(1)
     */
    protected $forecast;

    /**
     * @ORM\OneToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Person", mappedBy="forecast_staff_person")
     * @ORM\JoinColumn(name="staff_person_id", referencedColumnName="id", nullable=TRUE)
     * @Groups({"api", "detail", "simple"})
     * @MaxDepth(1)
     */
    public $staff_person;


    /**
     * Set jobTitle
     *
     * @param string $jobTitle
     *
     * @return ForecastStaffPerson
     */
    public function setJobTitle($jobTitle)
    {
        $this->job_title = $jobTitle;

        return $this;
    }

    /**
     * Get jobTitle
     *
     * @return string
     */
    public function getJobTitle()
    {
        return $this->job_title;
    }

    /**
     * Set startingSalary
     *
     * @param integer $startingSalary
     *
     * @return ForecastStaffPerson
     */
    public function setStartingSalary($startingSalary)
    {
        $this->starting_salary = $startingSalary;

        return $this;
    }

    /**
     * Get startingSalary
     *
     * @return integer
     */
    public function getStartingSalary()
    {
        return $this->starting_salary;
    }

    /**
     * Set currentSalary
     *
     * @param integer $currentSalary
     *
     * @return ForecastStaffPerson
     */
    public function setCurrentSalary($currentSalary)
    {
        $this->current_salary = $currentSalary;

        return $this;
    }

    /**
     * Get currentSalary
     *
     * @return integer
     */
    public function getCurrentSalary()
    {
        return $this->current_salary;
    }

    /**
     * Set hireDate
     *
     * @param \DateTime $hireDate
     *
     * @return ForecastStaffPerson
     */
    public function setHireDate($hireDate)
    {
        $this->hire_date = $hireDate;

        return $this;
    }

    /**
     * Get hireDate
     *
     * @return \DateTime
     */
    public function getHireDate()
    {
        return $this->hire_date;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return ForecastStaffPerson
     */
    public function setStartDate($startDate)
    {
        $this->start_date = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->start_date;
    }

    /**
     * Set terminationDate
     *
     * @param \DateTime $terminationDate
     *
     * @return ForecastStaffPerson
     */
    public function setTerminationDate($terminationDate)
    {
        $this->termination_date = $terminationDate;

        return $this;
    }

    /**
     * Get terminationDate
     *
     * @return \DateTime
     */
    public function getTerminationDate()
    {
        return $this->termination_date;
    }

    /**
     * Set resignationDate
     *
     * @param \DateTime $resignationDate
     *
     * @return ForecastStaffPerson
     */
    public function setResignationDate($resignationDate)
    {
        $this->resignation_date = $resignationDate;

        return $this;
    }

    /**
     * Get resignationDate
     *
     * @return \DateTime
     */
    public function getResignationDate()
    {
        return $this->resignation_date;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return ForecastStaffPerson
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
     * @return ForecastStaffPerson
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
     * Set staffPerson
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Person $staffPerson
     *
     * @return ForecastStaffPerson
     */
    public function setStaffPerson(\Tavro\Bundle\CoreBundle\Entity\Person $staffPerson)
    {
        $this->staff_person = $staffPerson;

        return $this;
    }

    /**
     * Get staffPerson
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\Person
     */
    public function getStaffPerson()
    {
        return $this->staff_person;
    }

    /**
     * Set forecast
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Forecast $forecast
     *
     * @return ForecastStaffPerson
     */
    public function setForecast(\Tavro\Bundle\CoreBundle\Entity\Forecast $forecast)
    {
        $this->forecast = $forecast;

        return $this;
    }

    /**
     * Get forecast
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\Forecast
     */
    public function getForecast()
    {
        return $this->forecast;
    }
}

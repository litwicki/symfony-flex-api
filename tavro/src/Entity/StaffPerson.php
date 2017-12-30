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
use Tavro\Model\Entity\StaffPersonModel;
use Tavro\Model\EntityInterface\AccountEntityInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Repository\AccountEntity\StaffPersonRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="tavro_staff_person")
 * @ExclusionPolicy("all")
 *
 */
class StaffPerson extends StaffPersonModel implements AccountEntityInterface
{

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\Account", inversedBy="staff_persons")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "detail"})
     * @MaxDepth(3)
     * @Expose
     */
    protected $account;

    /**
     * @ORM\OneToOne(targetEntity="Tavro\Entity\Person", inversedBy="staff_person")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "detail", "simple"})
     * @MaxDepth(1)
     * @Expose
     */
    public $person;


    /**
     * Set jobTitle
     *
     * @param string $jobTitle
     *
     * @return StaffPerson
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
     * @return StaffPerson
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
     * @return StaffPerson
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
     * @return StaffPerson
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
     * @return StaffPerson
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
     * @return StaffPerson
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
     * @return StaffPerson
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
     * @return StaffPerson
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
     * @param \Tavro\Entity\Account $account
     *
     * @return StaffPerson
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
     * Set person
     *
     * @param \Tavro\Entity\Person $person
     *
     * @return StaffPerson
     */
    public function setPerson(\Tavro\Entity\Person $person)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person
     *
     * @return \Tavro\Entity\Person
     */
    public function getPerson()
    {
        return $this->person;
    }
}

<?php

namespace Tavro\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Rhumsaa\Uuid\Uuid;

use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\XmlRoot;
use JMS\Serializer\Annotation\XmlNamespace;
use JMS\Serializer\Annotation\SerializedName;
use Doctrine\ORM\Mapping\Table;

use Symfony\Component\Validator\Constraints as Assert;

use Tavro\Bundle\CoreBundle\Model\UserInterface;
use Tavro\Bundle\CoreBundle\Model\Entity;
use Tavro\Bundle\CoreBundle\Model\EntityInterface;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Bundle\CoreBundle\Doctrine\Repository\Entity\PersonRepository")
 * @Table(name="tavro_person")
 *
 * @XmlRoot("person")
 * @XmlNamespace(uri="http://tavro.io/api/people")
 */
class Person extends Entity implements EntityInterface
{
    /**
     * @ORM\Column(type="string", length=255, nullable=TRUE)
     * @Groups({"api", "tavro", "simple", "typeahead"})
     */
    protected $first_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=TRUE)
     * @Groups({"api", "tavro", "simple"})
     */
    protected $middle_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=TRUE)
     * @Groups({"api", "tavro", "simple", "typeahead"})
     */
    protected $last_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=TRUE)
     * @Groups({"api", "tavro", "simple"})
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=TRUE)
     * @Groups({"api", "tavro", "simple"})
     */
    protected $suffix;

    /**
     * @ORM\Column(type="string", length=32, nullable=TRUE)
     * @Assert\Choice(
     *      choices = { "male", "female", "other", "robot" },
     *      message = "Choose a valid gender."
     * )
     * @Groups({"api", "tavro", "simple"})
     * @Accessor(getter="getGender", setter="setGender")
     * @MaxDepth(1)
     */
    protected $gender;

    /**
     * @ORM\Column(type="date", length=255, nullable=TRUE)
     * @Groups({"tavro"})
     */
    protected $birthday;

    /**
     * @ORM\Column(type="string", length=255, nullable=TRUE)
     * @Groups({"api", "tavro", "simple"})
     */
    protected $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=TRUE)
     * @Groups({"api", "tavro", "simple"})
     */
    protected $address2;

    /**
     * @ORM\Column(type="string", length=255, nullable=TRUE)
     * @Groups({"api", "tavro", "simple"})
     */
    protected $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=TRUE)
     * @Groups({"api", "tavro", "simple"})
     */
    protected $state;

    /**
     * @ORM\Column(type="string", length=255, nullable=TRUE)
     * @Groups({"api", "tavro", "simple"})
     */
    protected $zip;

    /**
     * @ORM\Column(type="string", unique=TRUE, nullable=TRUE)
     * @Groups({"api", "tavro", "simple", "typeahead"})
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=TRUE)
     * @Groups({"api", "tavro", "simple", "typeahead"})
     */
    protected $phone;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->create_date = new \DateTime();
        $this->update_date = new \DateTime();
    }

    public function __toString()
    {
        return sprintf('%s, %s', $this->last_name, $this->first_name);
    }


    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Person
     */
    public function setFirstName($firstName)
    {
        $this->first_name = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Set middleName
     *
     * @param string $middleName
     *
     * @return Person
     */
    public function setMiddleName($middleName)
    {
        $this->middle_name = $middleName;

        return $this;
    }

    /**
     * Get middleName
     *
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middle_name;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Person
     */
    public function setLastName($lastName)
    {
        $this->last_name = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Person
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
     * Set suffix
     *
     * @param string $suffix
     *
     * @return Person
     */
    public function setSuffix($suffix)
    {
        $this->suffix = $suffix;

        return $this;
    }

    /**
     * Get suffix
     *
     * @return string
     */
    public function getSuffix()
    {
        return $this->suffix;
    }

    /**
     * Set gender
     *
     * @param string $gender
     *
     * @return Person
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Person
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set address2
     *
     * @param string $address2
     *
     * @return Person
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;

        return $this;
    }

    /**
     * Get address2
     *
     * @return string
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Person
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set state
     *
     * @param string $state
     *
     * @return Person
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set zip
     *
     * @param string $zip
     *
     * @return Person
     */
    public function setZip($zip)
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * Get zip
     *
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Person
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Person
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return Person
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
     * Set birthday
     *
     * @param \DateTime $birthday
     *
     * @return Person
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }
}

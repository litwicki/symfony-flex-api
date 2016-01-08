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
use Tavro\Bundle\CoreBundle\Model\ApiEntity;

/**
 * @ORM\Entity
 * @Table(name="tavro_user")
 *
 * @XmlRoot("user")
 * @XmlNamespace(uri="http://tavromods.com/api/users")
 */
class User extends ApiEntity
{
    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Groups({"api", "tavro", "summary", "typeahead"})
     */
    protected $first_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Groups({"api", "tavro", "summary", "typeahead"})
     */
    protected $last_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"api", "tavro", "summary", "typeahead"})
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Groups({"api", "tavro", "summary"})
     */
    protected $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Groups({"api", "tavro", "summary"})
     */
    protected $address2;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Groups({"api", "tavro", "summary"})
     */
    protected $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Groups({"api", "tavro", "summary"})
     */
    protected $state;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Groups({"api", "tavro", "summary"})
     */
    protected $zip;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Groups({"api", "tavro", "summary", "typeahead"})
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Groups({"api", "tavro", "summary", "typeahead"})
     */
    protected $shares;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->create_date = new \DateTime();
        $this->update_date = new \DateTime();
    }

    public function __toString()
    {
        return $this->username;
    }

}

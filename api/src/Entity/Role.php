<?php

namespace Api\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

use Symfony\Component\Security\Core\Role\RoleInterface;
use Api\Bundle\CoreBundle\Model\EntityInterface\EntityInterface;
use Api\Bundle\CoreBundle\Model\Entity\Entity;

use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping\Table;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Api\Bundle\CoreBundle\Repository\Entity\RoleRepository")
 * @Table(name="tavro_role")
 * @ExclusionPolicy("all")
 *
 */
class Role extends Entity implements RoleInterface, EntityInterface, \Serializable
{

    /**
     * @ORM\Column(type="string", unique=TRUE, nullable=TRUE)
     * @Groups({"api", "detail"})
     * @Expose
     */
    protected $name;

    /**
     * @ORM\Column(type="string", unique=TRUE, nullable=TRUE)
     * @Groups({"api", "detail"})
     * @Expose
     */
    protected $role;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="roles", cascade={"persist"})
     */
    protected $users;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->role;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Role
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set role
     *
     * @param string $role
     * @return Role
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string 
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Add user
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     * @return Role
     */
    public function addUser(\Tavro\Bundle\CoreBundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\User $user
     */
    public function removeUser(\Tavro\Bundle\CoreBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get user
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        /*
         * ! Don't serialize $users field !
         */
        return \serialize(array(
            $this->id,
            $this->role
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->role
        ) = \unserialize($serialized);
    }


    /**
     * Set body
     *
     * @param string $body
     *
     * @return Role
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
}
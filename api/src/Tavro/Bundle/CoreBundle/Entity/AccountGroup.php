<?php

namespace Tavro\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping\Table;
use JMS\Serializer\Annotation\MaxDepth;

use Tavro\Bundle\CoreBundle\Model\Entity;
use Tavro\Bundle\CoreBundle\Model\EntityInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Bundle\CoreBundle\Repository\AccountGroupRepository")
 * @ORM\HasLifecycleCallbacks
 * @Table(name="tavro_account_group")
 */
class AccountGroup extends Entity implements EntityInterface
{
    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\User", inversedBy="account_groups")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "tavro", "simple"})
     * @MaxDepth(1)
     */
    protected $user;

    /**
     * @ORM\Column(type="string", unique=TRUE, length=500, nullable=FALSE)
     * @Groups({"api", "tavro", "simple"})
     */
    protected $name;

    /**
     * @ORM\Column(type="string", unique=TRUE, length=500, nullable=FALSE)
     * @Groups({"api", "tavro", "simple"})
     */
    protected $name_clean;

}

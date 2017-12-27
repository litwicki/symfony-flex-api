<?php
namespace Tavro\Bundle\CoreBundle\Model\Entity;

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
use Tavro\Bundle\CoreBundle\Model\EntityInterface\AccountEntityInterface;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class StaffPersonModel extends Entity
{

    /**
     * @ORM\Column(type="string", length=255, nullable=TRUE)
     * @Groups({"api", "detail", "simple", "typeahead"})
     */
    protected $job_title;

    /**
     * @ORM\Column(type="integer", nullable=FALSE, options={"default" : 0})
     * @Groups({"api", "detail", "simple"})
     */
    protected $starting_salary;

    /**
     * @ORM\Column(type="integer", nullable=FALSE, options={"default" : 0})
     * @Groups({"api", "detail", "simple"})
     */
    protected $current_salary;

    /**
     * @Type("datetime")
     * @ORM\Column(type="datetime", length=1000, nullable=true)
     * @Groups({"api", "detail"})
     */
    protected $hire_date;

    /**
     * @Type("datetime")
     * @ORM\Column(type="datetime", length=1000, nullable=true)
     * @Groups({"api", "detail"})
     */
    protected $start_date;

    /**
     * @Type("datetime")
     * @ORM\Column(type="datetime", length=1000, nullable=true)
     * @Groups({"api", "detail"})
     */
    protected $termination_date;

    /**
     * @Type("datetime")
     * @ORM\Column(type="datetime", length=1000, nullable=true)
     * @Groups({"api", "detail"})
     */
    protected $resignation_date;

}
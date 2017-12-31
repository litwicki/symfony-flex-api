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
 * @ORM\Entity(repositoryClass="Tavro\Repository\Entity\DashboardRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="tavro_dashboard")
 * @ExclusionPolicy("all")
 *
 */
class Dashboard extends Entity implements AccountEntityInterface
{

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\Account", inversedBy="dashboards")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"detail"})
     * @MaxDepth(1)
     */
    protected $account;

    /**
     * @ORM\Column(type="string", length=255, nullable=FALSE)
     * @Groups({"api", "detail", "simple"})
     * @Expose
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Entity\DashboardMetric", mappedBy="dashboard", cascade={"remove"})
     * @Groups({"api", "detail", "simple"})
     * @Expose
     */
    protected $dashboard_metrics;

    public function __toString()
    {
        return $this->name;
    }


    /**
     * Set name
     *
     * @param string $name
     *
     * @return Dashboard
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
     * Set body
     *
     * @param string $body
     *
     * @return Dashboard
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
     * @return Dashboard
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
     * Add dashboardMetric
     *
     * @param \App\Entity\DashboardMetric $dashboardMetric
     *
     * @return Dashboard
     */
    public function addDashboardMetric(\Tavro\Entity\DashboardMetric $dashboardMetric)
    {
        $this->dashboard_metrics[] = $dashboardMetric;

        return $this;
    }

    /**
     * Remove dashboardMetric
     *
     * @param \App\Entity\DashboardMetric $dashboardMetric
     */
    public function removeDashboardMetric(\Tavro\Entity\DashboardMetric $dashboardMetric)
    {
        $this->dashboard_metrics->removeElement($dashboardMetric);
    }

    /**
     * Get dashboardMetrics
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDashboardMetrics()
    {
        return $this->dashboard_metrics;
    }
}

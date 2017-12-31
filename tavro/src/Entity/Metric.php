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
 * @ORM\Entity(repositoryClass="Tavro\Repository\Entity\MetricRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="tavro_metric")
 * @ExclusionPolicy("all")
 *
 */
class Metric extends Entity implements AccountEntityInterface
{

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\Account", inversedBy="metrics")
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
     * @ORM\Column(type="string", length=8000, nullable=FALSE)
     * @Groups({"api", "detail", "simple"})
     * @Expose
     */
    protected $chart;

    /**
     * @ORM\OneToMany(targetEntity="Tavro\Entity\DashboardMetric", mappedBy="metric", cascade={"remove"})
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
     * @return Metric
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
     * Set calculator
     *
     * @param string $calculator
     *
     * @return Metric
     */
    public function setCalculator($calculator)
    {
        $this->calculator = $calculator;

        return $this;
    }

    /**
     * Get calculator
     *
     * @return string
     */
    public function getCalculator()
    {
        return $this->calculator;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return Metric
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
     * Add dashboardMetric
     *
     * @param \App\Entity\DashboardMetric $dashboardMetric
     *
     * @return Metric
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

    /**
     * Set account
     *
     * @param \App\Entity\Account $account
     *
     * @return Metric
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
     * Set chart
     *
     * @param string $chart
     *
     * @return Metric
     */
    public function setChart($chart)
    {
        $this->chart = $chart;

        return $this;
    }

    /**
     * Get chart
     *
     * @return string
     */
    public function getChart()
    {
        return $this->chart;
    }
}

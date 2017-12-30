<?php

namespace Tavro\Entity;

use Doctrine\ORM\Mapping AS ORM;

use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping\Table;

use Tavro\Model\Entity\Entity;
use Tavro\Model\EntityInterface\EntityInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Repository\Entity\DashboardMetricRepository")
 * @ORM\Table(name="tavro_dashboard_read", uniqueConstraints={@ORM\UniqueConstraint(name="DASHBOARD_METRIC", columns={"metric_id", "dashboard_id"})})
 * @ExclusionPolicy("all")
 */
class DashboardMetric extends Entity implements EntityInterface
{

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\Metric", inversedBy="dashboards_read")
     * @ORM\JoinColumn(name="metric_id", referencedColumnName="id", nullable=FALSE)
     */
    protected $metric;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Entity\Dashboard", inversedBy="dashboards_read",)
     * @ORM\JoinColumn(name="dashboard_id", referencedColumnName="id", nullable=FALSE)
     */
    protected $dashboard;

    /**
     * Set metric
     *
     * @param \Tavro\Entity\Metric $metric
     * @return $this
     */
    public function setMetric(\Tavro\Entity\Metric $metric)
    {
        $this->metric = $metric;

        return $this;
    }

    /**
     * Get metric
     *
     * @return \Tavro\Entity\Metric
     */
    public function getMetric()
    {
        return $this->metric;
    }

    /**
     * Set dashboard
     *
     * @param \Tavro\Entity\dashboard $dashboard
     * @return $this
     */
    public function setDashboard(\Tavro\Entity\Dashboard $dashboard)
    {
        $this->dashboard = $dashboard;

        return $this;
    }

    /**
     * Get dashboard
     *
     * @return \Tavro\Entity\Dashboard
     */
    public function getDashboard()
    {
        return $this->dashboard;
    }


    /**
     * Set body
     *
     * @param string $body
     *
     * @return $this
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

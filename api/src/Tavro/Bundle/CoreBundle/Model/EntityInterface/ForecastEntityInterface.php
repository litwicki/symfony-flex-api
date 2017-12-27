<?php

namespace Tavro\Bundle\CoreBundle\Model\EntityInterface;

/**
 * Interface ForecastEntityInterface
 *
 * @package Tavro\Bundle\CoreBundle\Model\EntityInterface
 */
interface ForecastEntityInterface
{
    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Set status
     *
     * @param integer $status
     * @return $this
     */
    public function setStatus($status);

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus();

    /**
     * Set create_date
     *
     * @param \DateTime $createDate
     * @return $this
     */
    public function setCreateDate($createDate);

    /**
     * Get create_date
     *
     * @return \DateTime
     */
    public function getCreateDate();

    /**
     * Set update_date
     *
     * @param \DateTime $updateDate
     * @return $this
     */
    public function setUpdateDate($updateDate);

    /**
     * Get update_date
     *
     * @return \DateTime
     */
    public function getUpdateDate();

    /**
     * Set forecast
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Forecast $forecast
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\Forecast
     */
    public function setForecast(\Tavro\Bundle\CoreBundle\Entity\Forecast $forecast);

    /**
     * Get account
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\Forecast
     */
    public function getForecast();

}

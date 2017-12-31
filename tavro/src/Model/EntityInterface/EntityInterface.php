<?php

namespace App\Model\EntityInterface;

/**
 * Interface EntityInterface
 *
 * @package Tavro\Model\EntityInterface
 */
interface EntityInterface
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
     * Set body
     *
     * @param string $body
     *
     * @return $this
     */
    public function setBody($body);

    /**
     * Get body
     *
     * @return string
     */
    public function getBody();

}

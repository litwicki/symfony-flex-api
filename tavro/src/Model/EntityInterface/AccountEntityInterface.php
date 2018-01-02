<?php

namespace App\Model\EntityInterface;

/**
 * Interface AccountEntityInterface
 *
 * @package Tavro\Model\EntityInterface
 */
interface AccountEntityInterface
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
     * Set account
     *
     * @param \App\Entity\Account $account
     *
     * @return \Tavro\Entity\Account
     */
    public function setAccount(\Tavro\Entity\Account $account);

    /**
     * Get account
     *
     * @return \Tavro\Entity\Account
     */
    public function getAccount();

}
<?php

namespace Tavro\Bundle\CoreBundle\Model;

/**
 * Interface OrganizationEntityInterface
 *
 * @package Tavro\Bundle\CoreBundle\Model
 */
interface OrganizationEntityInterface
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
     * Set organization
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Organization $organization
     *
     * @return Expense
     */
    public function setOrganization(\Tavro\Bundle\CoreBundle\Entity\Organization $organization);

    /**
     * Get organization
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\Organization
     */
    public function getOrganization();

}

<?php

namespace Solucel\AdminBundle\Entity;

/**
 * AdminSetting
 */
class AdminSetting
{
    /**
     * @var integer
     */
    private $id;



    /**
     * @var \DateTime
     */
    private $updatedAt = '2001-01-01 00:00:00';

    /**
     * @var \Solucel\AdminBundle\Entity\Operator
     */
    private $operator;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return AdminSetting
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set operator
     *
     * @param \Solucel\AdminBundle\Entity\Operator $operator
     *
     * @return AdminSetting
     */
    public function setOperator(\Solucel\AdminBundle\Entity\Operator $operator = null)
    {
        $this->operator = $operator;

        return $this;
    }

    /**
     * Get operator
     *
     * @return \Solucel\AdminBundle\Entity\Operator
     */
    public function getOperator()
    {
        return $this->operator;
    }
    /**
     * @var \Solucel\AdminBundle\Entity\User
     */
    private $updatedBy;


    /**
     * Set updatedBy
     *
     * @param \Solucel\AdminBundle\Entity\User $updatedBy
     *
     * @return AdminSetting
     */
    public function setUpdatedBy(\Solucel\AdminBundle\Entity\User $updatedBy = null)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * Get updatedBy
     *
     * @return \Solucel\AdminBundle\Entity\User
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }
    /**
     * @var integer
     */

    /**
     * @var integer
     */
    private $entryEstimatedTime = 0;


    /**
     * Set entryEstimatedTime
     *
     * @param integer $entryEstimatedTime
     *
     * @return AdminSetting
     */
    public function setEntryEstimatedTime($entryEstimatedTime)
    {
        $this->entryEstimatedTime = $entryEstimatedTime;

        return $this;
    }

    /**
     * Get entryEstimatedTime
     *
     * @return integer
     */
    public function getEntryEstimatedTime()
    {
        return $this->entryEstimatedTime;
    }


    public function setUpdatedAtValue()
    {
        // Add your code here
        $this->updatedAt = new \DateTime();

    }


}

<?php

namespace Solucel\AdminBundle\Entity;

/**
 * TimeLog
 */
class TimeLog
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $action = '';

    /**
     * @var integer
     */
    private $logTimeMinutes = 0;

    /**
     * @var \DateTime
     */
    private $createdAt = '2001-01-01 00:00:00';

    /**
     * @var \Solucel\AdminBundle\Entity\RepairOrder
     */
    private $repairOrder;

    /**
     * @var \Solucel\AdminBundle\Entity\User
     */
    private $user;


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
     * Set action
     *
     * @param string $action
     *
     * @return TimeLog
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set logTimeMinutes
     *
     * @param integer $logTimeMinutes
     *
     * @return TimeLog
     */
    public function setLogTimeMinutes($logTimeMinutes)
    {
        $this->logTimeMinutes = $logTimeMinutes;

        return $this;
    }

    /**
     * Get logTimeMinutes
     *
     * @return integer
     */
    public function getLogTimeMinutes()
    {
        return $this->logTimeMinutes;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return TimeLog
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set repairOrder
     *
     * @param \Solucel\AdminBundle\Entity\RepairOrder $repairOrder
     *
     * @return TimeLog
     */
    public function setRepairOrder(\Solucel\AdminBundle\Entity\RepairOrder $repairOrder = null)
    {
        $this->repairOrder = $repairOrder;

        return $this;
    }

    /**
     * Get repairOrder
     *
     * @return \Solucel\AdminBundle\Entity\RepairOrder
     */
    public function getRepairOrder()
    {
        return $this->repairOrder;
    }

    /**
     * Set user
     *
     * @param \Solucel\AdminBundle\Entity\User $user
     *
     * @return TimeLog
     */
    public function setUser(\Solucel\AdminBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Solucel\AdminBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    public function setCreatedAtValue()
    {
        // Add your code here
        $this->createdAt = new \DateTime();

    }

}


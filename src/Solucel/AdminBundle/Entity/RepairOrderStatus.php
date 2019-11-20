<?php

namespace Solucel\AdminBundle\Entity;

/**
 * RepairOrderStatus
 */
class RepairOrderStatus
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \Solucel\AdminBundle\Entity\User
     */
    private $createdBy;

    /**
     * @var \Solucel\AdminBundle\Entity\RepairOrder
     */
    private $repairOrder;


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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return RepairOrderStatus
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function setCreatedAtValue()
    {
        // Add your code here
        $this->createdAt = new \DateTime();
	
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
     * Set createdBy
     *
     * @param \Solucel\AdminBundle\Entity\User $createdBy
     *
     * @return RepairOrderStatus
     */
    public function setCreatedBy(\Solucel\AdminBundle\Entity\User $createdBy = null)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return \Solucel\AdminBundle\Entity\User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set repairOrder
     *
     * @param \Solucel\AdminBundle\Entity\RepairOrder $repairOrder
     *
     * @return RepairOrderStatus
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
     * @var \Solucel\AdminBundle\Entity\RepairStatus
     */
    private $repairStatus;


    /**
     * Set repairStatus
     *
     * @param \Solucel\AdminBundle\Entity\RepairStatus $repairStatus
     *
     * @return RepairOrderStatus
     */
    public function setRepairStatus(\Solucel\AdminBundle\Entity\RepairStatus $repairStatus = null)
    {
        $this->repairStatus = $repairStatus;

        return $this;
    }

    /**
     * Get repairStatus
     *
     * @return \Solucel\AdminBundle\Entity\RepairStatus
     */
    public function getRepairStatus()
    {
        return $this->repairStatus;
    }
}

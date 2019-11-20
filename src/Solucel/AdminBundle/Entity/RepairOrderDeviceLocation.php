<?php

namespace Solucel\AdminBundle\Entity;

/**
 * RepairOrderDeviceLocation
 */
class RepairOrderDeviceLocation
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
     * @var \Solucel\AdminBundle\Entity\RepairOrder
     */
    private $repairOrder;

    /**
     * @var \Solucel\AdminBundle\Entity\DeviceLocation
     */
    private $deviceLocation;


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
     * @return RepairOrderDeviceLocation
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
     * Set repairOrder
     *
     * @param \Solucel\AdminBundle\Entity\RepairOrder $repairOrder
     *
     * @return RepairOrderDeviceLocation
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
     * Set deviceLocation
     *
     * @param \Solucel\AdminBundle\Entity\DeviceLocation $deviceLocation
     *
     * @return RepairOrderDeviceLocation
     */
    public function setDeviceLocation(\Solucel\AdminBundle\Entity\DeviceLocation $deviceLocation = null)
    {
        $this->deviceLocation = $deviceLocation;

        return $this;
    }

    /**
     * Get deviceLocation
     *
     * @return \Solucel\AdminBundle\Entity\DeviceLocation
     */
    public function getDeviceLocation()
    {
        return $this->deviceLocation;
    }
}

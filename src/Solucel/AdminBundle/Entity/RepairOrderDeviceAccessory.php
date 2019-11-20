<?php

namespace Solucel\AdminBundle\Entity;

/**
 * RepairOrderDeviceAccessory
 */
class RepairOrderDeviceAccessory
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Solucel\AdminBundle\Entity\RepairOrder
     */
    private $repairOrder;

    /**
     * @var \Solucel\AdminBundle\Entity\DeviceAccessory
     */
    private $deviceAccessory;


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
     * Set repairOrder
     *
     * @param \Solucel\AdminBundle\Entity\RepairOrder $repairOrder
     *
     * @return RepairOrderDeviceAccessory
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
     * Set deviceAccessory
     *
     * @param \Solucel\AdminBundle\Entity\DeviceAccessory $deviceAccessory
     *
     * @return RepairOrderDeviceAccessory
     */
    public function setDeviceAccessory(\Solucel\AdminBundle\Entity\DeviceAccessory $deviceAccessory = null)
    {
        $this->deviceAccessory = $deviceAccessory;

        return $this;
    }

    /**
     * Get deviceAccessory
     *
     * @return \Solucel\AdminBundle\Entity\DeviceAccessory
     */
    public function getDeviceAccessory()
    {
        return $this->deviceAccessory;
    }
}

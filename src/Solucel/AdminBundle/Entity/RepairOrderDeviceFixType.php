<?php

namespace Solucel\AdminBundle\Entity;

/**
 * RepairOrderDeviceFixType
 */
class RepairOrderDeviceFixType
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Solucel\AdminBundle\Entity\DeviceFixType
     */
    private $deviceFixType;

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
     * Set deviceFixType
     *
     * @param \Solucel\AdminBundle\Entity\DeviceFixType $deviceFixType
     *
     * @return RepairOrderDeviceFixType
     */
    public function setDeviceFixType(\Solucel\AdminBundle\Entity\DeviceFixType $deviceFixType = null)
    {
        $this->deviceFixType = $deviceFixType;

        return $this;
    }

    /**
     * Get deviceFixType
     *
     * @return \Solucel\AdminBundle\Entity\DeviceFixType
     */
    public function getDeviceFixType()
    {
        return $this->deviceFixType;
    }

    /**
     * Set repairOrder
     *
     * @param \Solucel\AdminBundle\Entity\RepairOrder $repairOrder
     *
     * @return RepairOrderDeviceFixType
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
}

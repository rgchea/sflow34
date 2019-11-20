<?php

namespace Solucel\AdminBundle\Entity;

/**
 * RepairOrderDeviceDefect
 */
class RepairOrderDeviceDefect
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name = '';

    /**
     * @var \Solucel\AdminBundle\Entity\DeviceDefect
     */
    private $deviceDefectType;

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
     * Set name
     *
     * @param string $name
     *
     * @return RepairOrderDeviceDefect
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set deviceDefectType
     *
     * @param \Solucel\AdminBundle\Entity\DeviceDefect $deviceDefectType
     *
     * @return RepairOrderDeviceDefect
     */
    public function setDeviceDefectType(\Solucel\AdminBundle\Entity\DeviceDefect $deviceDefectType = null)
    {
        $this->deviceDefectType = $deviceDefectType;

        return $this;
    }

    /**
     * Get deviceDefectType
     *
     * @return \Solucel\AdminBundle\Entity\DeviceDefect
     */
    public function getDeviceDefectType()
    {
        return $this->deviceDefectType;
    }

    /**
     * Set repairOrder
     *
     * @param \Solucel\AdminBundle\Entity\RepairOrder $repairOrder
     *
     * @return RepairOrderDeviceDefect
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
     * @var \Solucel\AdminBundle\Entity\DeviceDefect
     */
    private $deviceDefect;


    /**
     * Set deviceDefect
     *
     * @param \Solucel\AdminBundle\Entity\DeviceDefect $deviceDefect
     *
     * @return RepairOrderDeviceDefect
     */
    public function setDeviceDefect(\Solucel\AdminBundle\Entity\DeviceDefect $deviceDefect = null)
    {
        $this->deviceDefect = $deviceDefect;

        return $this;
    }

    /**
     * Get deviceDefect
     *
     * @return \Solucel\AdminBundle\Entity\DeviceDefect
     */
    public function getDeviceDefect()
    {
        return $this->deviceDefect;
    }
}

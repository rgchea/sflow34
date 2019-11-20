<?php

namespace Solucel\AdminBundle\Entity;

/**
 * RepairOrderDeviceReplacement
 */
class RepairOrderDeviceReplacement
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
     * @var \Solucel\AdminBundle\Entity\DeviceReplacement
     */
    private $deviceReplacement;


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
     * @return RepairOrderDeviceReplacement
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
     * Set deviceReplacement
     *
     * @param \Solucel\AdminBundle\Entity\DeviceReplacement $deviceReplacement
     *
     * @return RepairOrderDeviceReplacement
     */
    public function setDeviceReplacement(\Solucel\AdminBundle\Entity\DeviceReplacement $deviceReplacement = null)
    {
        $this->deviceReplacement = $deviceReplacement;

        return $this;
    }

    /**
     * Get deviceReplacement
     *
     * @return \Solucel\AdminBundle\Entity\DeviceReplacement
     */
    public function getDeviceReplacement()
    {
        return $this->deviceReplacement;
    }
    /**
     * @var integer
     */
    private $quantity = 1;


    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return RepairOrderDeviceReplacement
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }
    /**
     * @var float
     */
    private $price = '0.00';

    /**
     * @var float
     */
    private $cost = '0.00';


    /**
     * Set price
     *
     * @param float $price
     *
     * @return RepairOrderDeviceReplacement
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set cost
     *
     * @param float $cost
     *
     * @return RepairOrderDeviceReplacement
     */
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * Get cost
     *
     * @return float
     */
    public function getCost()
    {
        return $this->cost;
    }
    /**
     * @var boolean
     */
    private $available = false;


    /**
     * Set available
     *
     * @param boolean $available
     *
     * @return RepairOrderDeviceReplacement
     */
    public function setAvailable($available)
    {
        $this->available = $available;

        return $this;
    }

    /**
     * Get available
     *
     * @return boolean
     */
    public function getAvailable()
    {
        return $this->available;
    }
}

<?php

namespace Solucel\AdminBundle\Entity;

/**
 * RepairOrderDeliveryDetail
 */
class RepairOrderDeliveryDetail
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
     * @var \Solucel\AdminBundle\Entity\RepairOrderDelivery
     */
    private $repairOrderDelivery;


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
     * @return RepairOrderDeliveryDetail
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
     * Set repairOrderDelivery
     *
     * @param \Solucel\AdminBundle\Entity\RepairOrderDelivery $repairOrderDelivery
     *
     * @return RepairOrderDeliveryDetail
     */
    public function setRepairOrderDelivery(\Solucel\AdminBundle\Entity\RepairOrderDelivery $repairOrderDelivery = null)
    {
        $this->repairOrderDelivery = $repairOrderDelivery;

        return $this;
    }

    /**
     * Get repairOrderDelivery
     *
     * @return \Solucel\AdminBundle\Entity\RepairOrderDelivery
     */
    public function getRepairOrderDelivery()
    {
        return $this->repairOrderDelivery;
    }
}

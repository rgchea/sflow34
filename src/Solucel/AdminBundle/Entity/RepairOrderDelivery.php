<?php

namespace Solucel\AdminBundle\Entity;

/**
 * RepairOrderDelivery
 */
class RepairOrderDelivery
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $deliveryDateSent;

    /**
     * @var \DateTime
     */
    private $deliveryDateReceived;

    /**
     * @var integer
     */
    private $deliveryFrom;

    /**
     * @var integer
     */
    private $deliveryTo;

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
     * Set deliveryDateSent
     *
     * @param \DateTime $deliveryDateSent
     *
     * @return RepairOrderDelivery
     */
    public function setDeliveryDateSent($deliveryDateSent)
    {
        $this->deliveryDateSent = $deliveryDateSent;

        return $this;
    }

    /**
     * Get deliveryDateSent
     *
     * @return \DateTime
     */
    public function getDeliveryDateSent()
    {
        return $this->deliveryDateSent;
    }

    /**
     * Set deliveryDateReceived
     *
     * @param \DateTime $deliveryDateReceived
     *
     * @return RepairOrderDelivery
     */
    public function setDeliveryDateReceived($deliveryDateReceived)
    {
        $this->deliveryDateReceived = $deliveryDateReceived;

        return $this;
    }

    /**
     * Get deliveryDateReceived
     *
     * @return \DateTime
     */
    public function getDeliveryDateReceived()
    {
        return $this->deliveryDateReceived;
    }

    /**
     * Set deliveryFrom
     *
     * @param integer $deliveryFrom
     *
     * @return RepairOrderDelivery
     */
    public function setDeliveryFrom($deliveryFrom)
    {
        $this->deliveryFrom = $deliveryFrom;

        return $this;
    }

    /**
     * Get deliveryFrom
     *
     * @return integer
     */
    public function getDeliveryFrom()
    {
        return $this->deliveryFrom;
    }

    /**
     * Set deliveryTo
     *
     * @param integer $deliveryTo
     *
     * @return RepairOrderDelivery
     */
    public function setDeliveryTo($deliveryTo)
    {
        $this->deliveryTo = $deliveryTo;

        return $this;
    }

    /**
     * Get deliveryTo
     *
     * @return integer
     */
    public function getDeliveryTo()
    {
        return $this->deliveryTo;
    }

    /**
     * Set repairOrder
     *
     * @param \Solucel\AdminBundle\Entity\RepairOrder $repairOrder
     *
     * @return RepairOrderDelivery
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
     * @var \Solucel\AdminBundle\Entity\Agency
     */
    private $deliveryFromAgency;

    /**
     * @var \Solucel\AdminBundle\Entity\ServiceCenter
     */
    private $deliveryToServiceCenter;

    /**
     * @var \Solucel\AdminBundle\Entity\ServiceCenter
     */
    private $deliveryFromServiceCenter;

    /**
     * @var \Solucel\AdminBundle\Entity\Agency
     */
    private $deliveryToAgency;


    /**
     * Set deliveryFromAgency
     *
     * @param \Solucel\AdminBundle\Entity\Agency $deliveryFromAgency
     *
     * @return RepairOrderDelivery
     */
    public function setDeliveryFromAgency(\Solucel\AdminBundle\Entity\Agency $deliveryFromAgency = null)
    {
        $this->deliveryFromAgency = $deliveryFromAgency;

        return $this;
    }

    /**
     * Get deliveryFromAgency
     *
     * @return \Solucel\AdminBundle\Entity\Agency
     */
    public function getDeliveryFromAgency()
    {
        return $this->deliveryFromAgency;
    }

    /**
     * Set deliveryToServiceCenter
     *
     * @param \Solucel\AdminBundle\Entity\ServiceCenter $deliveryToServiceCenter
     *
     * @return RepairOrderDelivery
     */
    public function setDeliveryToServiceCenter(\Solucel\AdminBundle\Entity\ServiceCenter $deliveryToServiceCenter = null)
    {
        $this->deliveryToServiceCenter = $deliveryToServiceCenter;

        return $this;
    }

    /**
     * Get deliveryToServiceCenter
     *
     * @return \Solucel\AdminBundle\Entity\ServiceCenter
     */
    public function getDeliveryToServiceCenter()
    {
        return $this->deliveryToServiceCenter;
    }

    /**
     * Set deliveryFromServiceCenter
     *
     * @param \Solucel\AdminBundle\Entity\ServiceCenter $deliveryFromServiceCenter
     *
     * @return RepairOrderDelivery
     */
    public function setDeliveryFromServiceCenter(\Solucel\AdminBundle\Entity\ServiceCenter $deliveryFromServiceCenter = null)
    {
        $this->deliveryFromServiceCenter = $deliveryFromServiceCenter;

        return $this;
    }

    /**
     * Get deliveryFromServiceCenter
     *
     * @return \Solucel\AdminBundle\Entity\ServiceCenter
     */
    public function getDeliveryFromServiceCenter()
    {
        return $this->deliveryFromServiceCenter;
    }

    /**
     * Set deliveryToAgency
     *
     * @param \Solucel\AdminBundle\Entity\Agency $deliveryToAgency
     *
     * @return RepairOrderDelivery
     */
    public function setDeliveryToAgency(\Solucel\AdminBundle\Entity\Agency $deliveryToAgency = null)
    {
        $this->deliveryToAgency = $deliveryToAgency;

        return $this;
    }

    /**
     * Get deliveryToAgency
     *
     * @return \Solucel\AdminBundle\Entity\Agency
     */
    public function getDeliveryToAgency()
    {
        return $this->deliveryToAgency;
    }
    /**
     * @var string
     */
    private $deliveryStatus = 'En Transito';


    /**
     * Set deliveryStatus
     *
     * @param string $deliveryStatus
     *
     * @return RepairOrderDelivery
     */
    public function setDeliveryStatus($deliveryStatus)
    {
        $this->deliveryStatus = $deliveryStatus;

        return $this;
    }

    /**
     * Get deliveryStatus
     *
     * @return string
     */
    public function getDeliveryStatus()
    {
        return $this->deliveryStatus;
    }
}

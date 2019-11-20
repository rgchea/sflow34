<?php

namespace Solucel\AdminBundle\Entity;

/**
 * RepairOrderFix
 */
class RepairOrderFix
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $version = '0';

    /**
     * @var string
     */
    private $fixDetail;

    /**
     * @var string
     */
    private $deviceReceivedStatus;

    /**
     * @var string
     */
    private $warrantyComment;

    /**
     * @var float
     */
    private $fixingPrice = '0.00';

    /**
     * @var string
     */
    private $imeiNewDeviceChange = '';

    /**
     * @var integer
     */
    private $serialNumberChange;

    /**
     * @var \Solucel\AdminBundle\Entity\DeviceBrand
     */
    private $deviceBrandChange;

    /**
     * @var \Solucel\AdminBundle\Entity\User
     */
    private $asignedBy;

    /**
     * @var \Solucel\AdminBundle\Entity\User
     */
    private $asignedTo;

    /**
     * @var \Solucel\AdminBundle\Entity\RepairOrder
     */
    private $repairOrder;

    /**
     * @var \Solucel\AdminBundle\Entity\DeviceFixType
     */
    private $deviceFixType;


    /**
     * @var \Solucel\AdminBundle\Entity\DeviceModel
     */
    private $deviceModelChange;


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
     * Set version
     *
     * @param integer $version
     *
     * @return RepairOrderFix
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get version
     *
     * @return integer
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set fixDetail
     *
     * @param string $fixDetail
     *
     * @return RepairOrderFix
     */
    public function setFixDetail($fixDetail)
    {
        $this->fixDetail = $fixDetail;

        return $this;
    }

    /**
     * Get fixDetail
     *
     * @return string
     */
    public function getFixDetail()
    {
        return $this->fixDetail;
    }

    /**
     * Set deviceReceivedStatus
     *
     * @param string $deviceReceivedStatus
     *
     * @return RepairOrderFix
     */
    public function setDeviceReceivedStatus($deviceReceivedStatus)
    {
        $this->deviceReceivedStatus = $deviceReceivedStatus;

        return $this;
    }

    /**
     * Get deviceReceivedStatus
     *
     * @return string
     */
    public function getDeviceReceivedStatus()
    {
        return $this->deviceReceivedStatus;
    }

    /**
     * Set warrantyComment
     *
     * @param string $warrantyComment
     *
     * @return RepairOrderFix
     */
    public function setWarrantyComment($warrantyComment)
    {
        $this->warrantyComment = $warrantyComment;

        return $this;
    }

    /**
     * Get warrantyComment
     *
     * @return string
     */
    public function getWarrantyComment()
    {
        return $this->warrantyComment;
    }

    /**
     * Set fixingPrice
     *
     * @param float $fixingPrice
     *
     * @return RepairOrderFix
     */
    public function setFixingPrice($fixingPrice)
    {
        $this->fixingPrice = $fixingPrice;

        return $this;
    }

    /**
     * Get fixingPrice
     *
     * @return float
     */
    public function getFixingPrice()
    {
        return $this->fixingPrice;
    }

    /**
     * Set imeiNewDeviceChange
     *
     * @param string $imeiNewDeviceChange
     *
     * @return RepairOrderFix
     */
    public function setImeiNewDeviceChange($imeiNewDeviceChange)
    {
        $this->imeiNewDeviceChange = $imeiNewDeviceChange;

        return $this;
    }

    /**
     * Get imeiNewDeviceChange
     *
     * @return string
     */
    public function getImeiNewDeviceChange()
    {
        return $this->imeiNewDeviceChange;
    }

    /**
     * Set serialNumberChange
     *
     * @param integer $serialNumberChange
     *
     * @return RepairOrderFix
     */
    public function setSerialNumberChange($serialNumberChange)
    {
        $this->serialNumberChange = $serialNumberChange;

        return $this;
    }

    /**
     * Get serialNumberChange
     *
     * @return integer
     */
    public function getSerialNumberChange()
    {
        return $this->serialNumberChange;
    }

    /**
     * Set deviceBrandChange
     *
     * @param \Solucel\AdminBundle\Entity\DeviceBrand $deviceBrandChange
     *
     * @return RepairOrderFix
     */
    public function setDeviceBrandChange(\Solucel\AdminBundle\Entity\DeviceBrand $deviceBrandChange = null)
    {
        $this->deviceBrandChange = $deviceBrandChange;

        return $this;
    }

    /**
     * Get deviceBrandChange
     *
     * @return \Solucel\AdminBundle\Entity\DeviceBrand
     */
    public function getDeviceBrandChange()
    {
        return $this->deviceBrandChange;
    }

    /**
     * Set asignedBy
     *
     * @param \Solucel\AdminBundle\Entity\User $asignedBy
     *
     * @return RepairOrderFix
     */
    public function setAsignedBy(\Solucel\AdminBundle\Entity\User $asignedBy = null)
    {
        $this->asignedBy = $asignedBy;

        return $this;
    }

    /**
     * Get asignedBy
     *
     * @return \Solucel\AdminBundle\Entity\User
     */
    public function getAsignedBy()
    {
        return $this->asignedBy;
    }

    /**
     * Set asignedTo
     *
     * @param \Solucel\AdminBundle\Entity\User $asignedTo
     *
     * @return RepairOrderFix
     */
    public function setAsignedTo(\Solucel\AdminBundle\Entity\User $asignedTo = null)
    {
        $this->asignedTo = $asignedTo;

        return $this;
    }

    /**
     * Get asignedTo
     *
     * @return \Solucel\AdminBundle\Entity\User
     */
    public function getAsignedTo()
    {
        return $this->asignedTo;
    }

    /**
     * Set repairOrder
     *
     * @param \Solucel\AdminBundle\Entity\RepairOrder $repairOrder
     *
     * @return RepairOrderFix
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
     * Set deviceFixType
     *
     * @param \Solucel\AdminBundle\Entity\DeviceFixType $deviceFixType
     *
     * @return RepairOrderFix
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

    public function setDeviceModelChange(\Solucel\AdminBundle\Entity\DeviceModel $deviceModelChange = null)
    {
        $this->deviceModelChange = $deviceModelChange;

        return $this;
    }

    /**
     * Get deviceModelChange
     *
     * @return \Solucel\AdminBundle\Entity\DeviceModel
     */
    public function getDeviceModelChange()
    {
        return $this->deviceModelChange;
    }
    /**
     * @var \Solucel\AdminBundle\Entity\User
     */
    private $assignedBy;

    /**
     * @var \Solucel\AdminBundle\Entity\User
     */
    private $assignedTo;


    /**
     * Set assignedBy
     *
     * @param \Solucel\AdminBundle\Entity\User $assignedBy
     *
     * @return RepairOrderFix
     */
    public function setAssignedBy(\Solucel\AdminBundle\Entity\User $assignedBy = null)
    {
        $this->assignedBy = $assignedBy;

        return $this;
    }

    /**
     * Get assignedBy
     *
     * @return \Solucel\AdminBundle\Entity\User
     */
    public function getAssignedBy()
    {
        return $this->assignedBy;
    }

    /**
     * Set assignedTo
     *
     * @param \Solucel\AdminBundle\Entity\User $assignedTo
     *
     * @return RepairOrderFix
     */
    public function setAssignedTo(\Solucel\AdminBundle\Entity\User $assignedTo = null)
    {
        $this->assignedTo = $assignedTo;

        return $this;
    }

    /**
     * Get assignedTo
     *
     * @return \Solucel\AdminBundle\Entity\User
     */
    public function getAssignedTo()
    {
        return $this->assignedTo;
    }
    /**
     * @var integer
     */
    private $clientRepairmentRequest = '0';

    /**
     * @var integer
     */
    private $clientRepairmentConfirmation = '0';


    /**
     * Set clientRepairmentRequest
     *
     * @param integer $clientRepairmentRequest
     *
     * @return RepairOrderFix
     */
    public function setClientRepairmentRequest($clientRepairmentRequest)
    {
        $this->clientRepairmentRequest = $clientRepairmentRequest;

        return $this;
    }

    /**
     * Get clientRepairmentRequest
     *
     * @return integer
     */
    public function getClientRepairmentRequest()
    {
        return $this->clientRepairmentRequest;
    }

    /**
     * Set clientRepairmentConfirmation
     *
     * @param integer $clientRepairmentConfirmation
     *
     * @return RepairOrderFix
     */
    public function setClientRepairmentConfirmation($clientRepairmentConfirmation)
    {
        $this->clientRepairmentConfirmation = $clientRepairmentConfirmation;

        return $this;
    }

    /**
     * Get clientRepairmentConfirmation
     *
     * @return integer
     */
    public function getClientRepairmentConfirmation()
    {
        return $this->clientRepairmentConfirmation;
    }
    /**
     * @var integer
     */
    private $replacementEmail = '0';


    /**
     * Set replacementEmail
     *
     * @param integer $replacementEmail
     *
     * @return RepairOrderFix
     */
    public function setReplacementEmail($replacementEmail)
    {
        $this->replacementEmail = $replacementEmail;

        return $this;
    }

    /**
     * Get replacementEmail
     *
     * @return integer
     */
    public function getReplacementEmail()
    {
        return $this->replacementEmail;
    }
    /**
     * @var integer
     */
    private $orderConfirmation = '0';


    /**
     * Set orderConfirmation
     *
     * @param integer $orderConfirmation
     *
     * @return RepairOrderFix
     */
    public function setOrderConfirmation($orderConfirmation)
    {
        $this->orderConfirmation = $orderConfirmation;

        return $this;
    }

    /**
     * Get orderConfirmation
     *
     * @return integer
     */
    public function getOrderConfirmation()
    {
        return $this->orderConfirmation;
    }
    /**
     * @var boolean
     */
    private $hasWarranty = true;


    /**
     * Set hasWarranty
     *
     * @param boolean $hasWarranty
     *
     * @return RepairOrderFix
     */
    public function setHasWarranty($hasWarranty)
    {
        $this->hasWarranty = $hasWarranty;

        return $this;
    }

    /**
     * Get hasWarranty
     *
     * @return boolean
     */
    public function getHasWarranty()
    {
        return $this->hasWarranty;
    }
    /**
     * @var boolean
     */
    private $qualityControlApproved = true;


    /**
     * Set qualityControlApproved
     *
     * @param boolean $qualityControlApproved
     *
     * @return RepairOrderFix
     */
    public function setQualityControlApproved($qualityControlApproved)
    {
        $this->qualityControlApproved = $qualityControlApproved;

        return $this;
    }

    /**
     * Get qualityControlApproved
     *
     * @return boolean
     */
    public function getQualityControlApproved()
    {
        return $this->qualityControlApproved;
    }
    /**
     * @var boolean
     */
    private $isStorehouse = true;

    /**
     * @var string
     */
    private $requisitionNumber = '';


    /**
     * Set isStorehouse
     *
     * @param boolean $isStorehouse
     *
     * @return RepairOrderFix
     */
    public function setIsStorehouse($isStorehouse)
    {
        $this->isStorehouse = $isStorehouse;

        return $this;
    }

    /**
     * Get isStorehouse
     *
     * @return boolean
     */
    public function getIsStorehouse()
    {
        return $this->isStorehouse;
    }

    /**
     * Set requisitionNumber
     *
     * @param string $requisitionNumber
     *
     * @return RepairOrderFix
     */
    public function setRequisitionNumber($requisitionNumber)
    {
        $this->requisitionNumber = $requisitionNumber;

        return $this;
    }

    /**
     * Get requisitionNumber
     *
     * @return string
     */
    public function getRequisitionNumber()
    {
        return $this->requisitionNumber;
    }
    /**
     * @var string
     */
    private $softwareOut = '';


    /**
     * Set softwareOut
     *
     * @param string $softwareOut
     *
     * @return RepairOrderFix
     */
    public function setSoftwareOut($softwareOut)
    {
        $this->softwareOut = $softwareOut;

        return $this;
    }

    /**
     * Get softwareOut
     *
     * @return string
     */
    public function getSoftwareOut()
    {
        return $this->softwareOut;
    }
    /**
     * @var string
     */


    /**
     * @var \Solucel\AdminBundle\Entity\ActionReasonCode
     */
    private $actionReasonCode;


    /**
     * Set actionReasonCode
     *
     * @param \Solucel\AdminBundle\Entity\ActionReasonCode $actionReasonCode
     *
     * @return RepairOrderFix
     */
    public function setActionReasonCode(\Solucel\AdminBundle\Entity\ActionReasonCode $actionReasonCode = null)
    {
        $this->actionReasonCode = $actionReasonCode;

        return $this;
    }

    /**
     * Get actionReasonCode
     *
     * @return \Solucel\AdminBundle\Entity\ActionReasonCode
     */
    public function getActionReasonCode()
    {
        return $this->actionReasonCode;
    }
    /**
     * @var \Solucel\AdminBundle\Entity\ProblemFoundCode
     */
    private $problemFoundCode;



    /**
     * Set problemFoundCode
     *
     * @param \Solucel\AdminBundle\Entity\ProblemFoundCode $problemFoundCode
     *
     * @return RepairOrderFix
     */
    public function setProblemFoundCode(\Solucel\AdminBundle\Entity\ProblemFoundCode $problemFoundCode = null)
    {
        $this->problemFoundCode = $problemFoundCode;

        return $this;
    }

    /**
     * Get problemFoundCode
     *
     * @return \Solucel\AdminBundle\Entity\ProblemFoundCode
     */
    public function getProblemFoundCode()
    {
        return $this->problemFoundCode;
    }

    /**
     * @var \Solucel\AdminBundle\Entity\TransactionCode
     */
    private $transactionCode;


    /**
     * Set transactionCode
     *
     * @param \Solucel\AdminBundle\Entity\TransactionCode $transactionCode
     *
     * @return RepairOrderFix
     */
    public function setTransactionCode(\Solucel\AdminBundle\Entity\TransactionCode $transactionCode = null)
    {
        $this->transactionCode = $transactionCode;

        return $this;
    }

    /**
     * Get transactionCode
     *
     * @return \Solucel\AdminBundle\Entity\TransactionCode
     */
    public function getTransactionCode()
    {
        return $this->transactionCode;
    }
	
    /**
     * @var \DateTime
     */
    private $updatedAt;


    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return RepairOrderFix
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
	
    public function setUpdatedAtValue()
    {
        // Add your code here
        $this->updatedAt = new \DateTime();
	
    }	
}

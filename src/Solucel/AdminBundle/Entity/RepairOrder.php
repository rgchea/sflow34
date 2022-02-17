<?php

namespace Solucel\AdminBundle\Entity;

/**
 * RepairOrder
 */
class RepairOrder
{
    /**
     * @var integer
     */
    private $id;


    /**
     * @var string
     */
    private $devicePlan = 'Prepago';

    /**
     * @var string
     */
    private $deviceImei = '';

    /**
     * @var string
     */
    private $deviceMsn;

    /**
     * @var string
     */
    private $deviceXcvr = '';

    /**
     * @var string
     */
    private $deviceCodeFab = '';

    /**
     * @var string
     */
    private $deviceProblem;

    /**
     * @var string
     */
    private $deviceObservation;

    /**
     * @var string
     */
    private $deviceBorrowedImei = 'N/A';

    /**
     * @var string
     */
    private $invoceNumber = '';

    /**
     * @var float
     */
    private $price = 0.00;

    /**
     * @var string
     */
    private $deposit = 'N/A';

    /**
     * @var \DateTime
     */
    private $devicePurchaseDate;

    /**
     * @var \DateTime
     */
    private $estimatedDeliveryDate;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $finishedAt;

    /**
     * @var boolean
     */
    private $enable = true;

    /**
     * @var string
     */
    private $dispatchPhotoPath = '';

    /**
     * @var \Solucel\AdminBundle\Entity\User
     */
    private $createdBy;

    /**
     * @var \Solucel\AdminBundle\Entity\RepairOrderStatus
     */
    private $repairOrderStatus;

    /**
     * @var \Solucel\AdminBundle\Entity\RepairEntryType
     */
    private $repairEntryType;

    /**
     * @var \Solucel\AdminBundle\Entity\Operator
     */
    private $operator;

    /**
     * @var \Solucel\AdminBundle\Entity\Agency
     */
    private $agency;

    /**
     * @var \Solucel\AdminBundle\Entity\Client
     */
    private $client;

    /**
     * @var \Solucel\AdminBundle\Entity\DeviceBrand
     */
    private $deviceBrand;

    /**
     * @var \Solucel\AdminBundle\Entity\DeviceModel
     */
    private $deviceModel;

    /**
     * @var \Solucel\AdminBundle\Entity\ServiceCenter
     */
    private $serviceCenter;


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
     * Set devicePlan
     *
     * @param string $devicePlan
     *
     * @return RepairOrder
     */
    public function setDevicePlan($devicePlan)
    {
        $this->devicePlan = $devicePlan;

        return $this;
    }

    /**
     * Get devicePlan
     *
     * @return string
     */
    public function getDevicePlan()
    {
        return $this->devicePlan;
    }

    /**
     * Set deviceImei
     *
     * @param string $deviceImei
     *
     * @return RepairOrder
     */
    public function setDeviceImei($deviceImei)
    {
        $this->deviceImei = $deviceImei;

        return $this;
    }

    /**
     * Get deviceImei
     *
     * @return string
     */
    public function getDeviceImei()
    {
        return $this->deviceImei;
    }

    /**
     * Set deviceMsn
     *
     * @param string $deviceMsn
     *
     * @return RepairOrder
     */
    public function setDeviceMsn($deviceMsn)
    {
        $this->deviceMsn = $deviceMsn;

        return $this;
    }

    /**
     * Get deviceMsn
     *
     * @return string
     */
    public function getDeviceMsn()
    {
        return $this->deviceMsn;
    }

    /**
     * Set deviceXcvr
     *
     * @param string $deviceXcvr
     *
     * @return RepairOrder
     */
    public function setDeviceXcvr($deviceXcvr)
    {
        $this->deviceXcvr = $deviceXcvr;

        return $this;
    }

    /**
     * Get deviceXcvr
     *
     * @return string
     */
    public function getDeviceXcvr()
    {
        return $this->deviceXcvr;
    }

    /**
     * Set deviceCodeFab
     *
     * @param string $deviceCodeFab
     *
     * @return RepairOrder
     */
    public function setDeviceCodeFab($deviceCodeFab)
    {
        $this->deviceCodeFab = $deviceCodeFab;

        return $this;
    }

    /**
     * Get deviceCodeFab
     *
     * @return string
     */
    public function getDeviceCodeFab()
    {
        return $this->deviceCodeFab;
    }

    /**
     * Set deviceProblem
     *
     * @param string $deviceProblem
     *
     * @return RepairOrder
     */
    public function setDeviceProblem($deviceProblem)
    {
        $this->deviceProblem = $deviceProblem;

        return $this;
    }

    /**
     * Get deviceProblem
     *
     * @return string
     */
    public function getDeviceProblem()
    {
        return $this->deviceProblem;
    }

    /**
     * Set deviceObservation
     *
     * @param string $deviceObservation
     *
     * @return RepairOrder
     */
    public function setDeviceObservation($deviceObservation)
    {
        $this->deviceObservation = $deviceObservation;

        return $this;
    }

    /**
     * Get deviceObservation
     *
     * @return string
     */
    public function getDeviceObservation()
    {
        return $this->deviceObservation;
    }

    /**
     * Set deviceBorrowedImei
     *
     * @param string $deviceBorrowedImei
     *
     * @return RepairOrder
     */
    public function setDeviceBorrowedImei($deviceBorrowedImei)
    {
        $this->deviceBorrowedImei = $deviceBorrowedImei;

        return $this;
    }

    /**
     * Get deviceBorrowedImei
     *
     * @return string
     */
    public function getDeviceBorrowedImei()
    {
        return $this->deviceBorrowedImei;
    }

    /**
     * Set invoceNumber
     *
     * @param string $invoceNumber
     *
     * @return RepairOrder
     */
    public function setInvoceNumber($invoceNumber)
    {
        $this->invoceNumber = $invoceNumber;

        return $this;
    }

    /**
     * Get invoceNumber
     *
     * @return string
     */
    public function getInvoceNumber()
    {
        return $this->invoceNumber;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return RepairOrder
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
     * Set deposit
     *
     * @param string $deposit
     *
     * @return RepairOrder
     */
    public function setDeposit($deposit)
    {
        $this->deposit = $deposit;

        return $this;
    }

    /**
     * Get deposit
     *
     * @return string
     */
    public function getDeposit()
    {
        return $this->deposit;
    }

    /**
     * Set devicePurchaseDate
     *
     * @param \DateTime $devicePurchaseDate
     *
     * @return RepairOrder
     */
    public function setDevicePurchaseDate($devicePurchaseDate)
    {
        $this->devicePurchaseDate = $devicePurchaseDate;

        return $this;
    }

    /**
     * Get devicePurchaseDate
     *
     * @return \DateTime
     */
    public function getDevicePurchaseDate()
    {
        return $this->devicePurchaseDate;
    }

    /**
     * Set estimatedDeliveryDate
     *
     * @param \DateTime $estimatedDeliveryDate
     *
     * @return RepairOrder
     */
    public function setEstimatedDeliveryDate($estimatedDeliveryDate)
    {
        $this->estimatedDeliveryDate = $estimatedDeliveryDate;

        return $this;
    }

    /**
     * Get estimatedDeliveryDate
     *
     * @return \DateTime
     */
    public function getEstimatedDeliveryDate()
    {
        return $this->estimatedDeliveryDate;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return RepairOrder
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
     * Set finishedAt
     *
     * @param \DateTime $finishedAt
     *
     * @return RepairOrder
     */
    public function setFinishedAt($finishedAt)
    {
        $this->finishedAt = $finishedAt;

        return $this;
    }

    /**
     * Get finishedAt
     *
     * @return \DateTime
     */
    public function getFinishedAt()
    {
        return $this->finishedAt;
    }

    /**
     * Set enable
     *
     * @param boolean $enable
     *
     * @return RepairOrder
     */
    public function setEnable($enable)
    {
        $this->enable = $enable;

        return $this;
    }

    /**
     * Get enable
     *
     * @return boolean
     */
    public function getEnable()
    {
        return $this->enable;
    }

    /**
     * Set dispatchPhotoPath
     *
     * @param string $dispatchPhotoPath
     *
     * @return RepairOrder
     */
    public function setDispatchPhotoPath($dispatchPhotoPath)
    {
        $this->dispatchPhotoPath = $dispatchPhotoPath;

        return $this;
    }

    /**
     * Get dispatchPhotoPath
     *
     * @return string
     */
    public function getDispatchPhotoPath()
    {
        return $this->dispatchPhotoPath;
    }

    /**
     * Set createdBy
     *
     * @param \Solucel\AdminBundle\Entity\User $createdBy
     *
     * @return RepairOrder
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
     * Set repairOrderStatus
     *
     * @param \Solucel\AdminBundle\Entity\RepairOrderStatus $repairOrderStatus
     *
     * @return RepairOrder
     */
    public function setRepairOrderStatus(\Solucel\AdminBundle\Entity\RepairOrderStatus $repairOrderStatus = null)
    {
        $this->repairOrderStatus = $repairOrderStatus;

        return $this;
    }

    /**
     * Get repairOrderStatus
     *
     * @return \Solucel\AdminBundle\Entity\RepairOrderStatus
     */
    public function getRepairOrderStatus()
    {
        return $this->repairOrderStatus;
    }

    /**
     * Set repairEntryType
     *
     * @param \Solucel\AdminBundle\Entity\RepairEntryType $repairEntryType
     *
     * @return RepairOrder
     */
    public function setRepairEntryType(\Solucel\AdminBundle\Entity\RepairEntryType $repairEntryType = null)
    {
        $this->repairEntryType = $repairEntryType;

        return $this;
    }

    /**
     * Get repairEntryType
     *
     * @return \Solucel\AdminBundle\Entity\RepairEntryType
     */
    public function getRepairEntryType()
    {
        return $this->repairEntryType;
    }

    /**
     * Set operator
     *
     * @param \Solucel\AdminBundle\Entity\Operator $operator
     *
     * @return RepairOrder
     */
    public function setOperator(\Solucel\AdminBundle\Entity\Operator $operator = null)
    {
        $this->operator = $operator;

        return $this;
    }

    /**
     * Get operator
     *
     * @return \Solucel\AdminBundle\Entity\Operator
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Set agency
     *
     * @param \Solucel\AdminBundle\Entity\Agency $agency
     *
     * @return RepairOrder
     */
    public function setAgency(\Solucel\AdminBundle\Entity\Agency $agency = null)
    {
        $this->agency = $agency;

        return $this;
    }

    /**
     * Get agency
     *
     * @return \Solucel\AdminBundle\Entity\Agency
     */
    public function getAgency()
    {
        return $this->agency;
    }

    /**
     * Set client
     *
     * @param \Solucel\AdminBundle\Entity\Client $client
     *
     * @return RepairOrder
     */
    public function setClient(\Solucel\AdminBundle\Entity\Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \Solucel\AdminBundle\Entity\Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set deviceBrand
     *
     * @param \Solucel\AdminBundle\Entity\DeviceBrand $deviceBrand
     *
     * @return RepairOrder
     */
    public function setDeviceBrand(\Solucel\AdminBundle\Entity\DeviceBrand $deviceBrand = null)
    {
        $this->deviceBrand = $deviceBrand;

        return $this;
    }

    /**
     * Get deviceBrand
     *
     * @return \Solucel\AdminBundle\Entity\DeviceBrand
     */
    public function getDeviceBrand()
    {
        return $this->deviceBrand;
    }

    /**
     * Set deviceModel
     *
     * @param \Solucel\AdminBundle\Entity\DeviceModel $deviceModel
     *
     * @return RepairOrder
     */
    public function setDeviceModel(\Solucel\AdminBundle\Entity\DeviceModel $deviceModel = null)
    {
        $this->deviceModel = $deviceModel;

        return $this;
    }

    /**
     * Get deviceModel
     *
     * @return \Solucel\AdminBundle\Entity\DeviceModel
     */
    public function getDeviceModel()
    {
        return $this->deviceModel;
    }

    /**
     * Set serviceCenter
     *
     * @param \Solucel\AdminBundle\Entity\ServiceCenter $serviceCenter
     *
     * @return RepairOrder
     */
    public function setServiceCenter(\Solucel\AdminBundle\Entity\ServiceCenter $serviceCenter = null)
    {
        $this->serviceCenter = $serviceCenter;

        return $this;
    }

    /**
     * Get serviceCenter
     *
     * @return \Solucel\AdminBundle\Entity\ServiceCenter
     */
    public function getServiceCenter()
    {
        return $this->serviceCenter;
    }
    /**
     * @var \Solucel\AdminBundle\Entity\DeviceType
     */
    private $deviceType;

    /**
     * @var \Solucel\AdminBundle\Entity\DeviceColor
     */
    private $deviceColor;


    /**
     * Set deviceType
     *
     * @param \Solucel\AdminBundle\Entity\DeviceType $deviceType
     *
     * @return RepairOrder
     */
    public function setDeviceType(\Solucel\AdminBundle\Entity\DeviceType $deviceType = null)
    {
        $this->deviceType = $deviceType;

        return $this;
    }

    /**
     * Get deviceType
     *
     * @return \Solucel\AdminBundle\Entity\DeviceType
     */
    public function getDeviceType()
    {
        return $this->deviceType;
    }

    /**
     * Set deviceColor
     *
     * @param \Solucel\AdminBundle\Entity\DeviceColor $deviceColor
     *
     * @return RepairOrder
     */
    public function setDeviceColor(\Solucel\AdminBundle\Entity\DeviceColor $deviceColor = null)
    {
        $this->deviceColor = $deviceColor;

        return $this;
    }

    /**
     * Get deviceColor
     *
     * @return \Solucel\AdminBundle\Entity\DeviceColor
     */
    public function getDeviceColor()
    {
        return $this->deviceColor;
    }
    /**
     * @var boolean
     */
    private $enabled = true;


    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return RepairOrder
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }
    /**
     * @var string
     */
    private $invoiceNumber = '';


    /**
     * Set invoiceNumber
     *
     * @param string $invoiceNumber
     *
     * @return RepairOrder
     */
    public function setInvoiceNumber($invoiceNumber)
    {
        $this->invoiceNumber = $invoiceNumber;

        return $this;
    }

    /**
     * Get invoiceNumber
     *
     * @return string
     */
    public function getInvoiceNumber()
    {
        return $this->invoiceNumber;
    }
    /**
     * @var \DateTime
     */
    private $entryDate;


    /**
     * Set entryDate
     *
     * @param \DateTime $entryDate
     *
     * @return RepairOrder
     */
    public function setEntryDate($entryDate)
    {
        $this->entryDate = $entryDate;

        return $this;
    }

    /**
     * Get entryDate
     *
     * @return \DateTime
     */
    public function getEntryDate()
    {
        return $this->entryDate;
    }
    /**
     * @var boolean
     */
    private $humidity = true;


    /**
     * Set humidity
     *
     * @param boolean $humidity
     *
     * @return RepairOrder
     */
    public function setHumidity($humidity)
    {
        $this->humidity = $humidity;

        return $this;
    }

    /**
     * Get humidity
     *
     * @return boolean
     */
    public function getHumidity()
    {
        return $this->humidity;
    }
	
	
	public function __toString(){
		return ''.$this->getId();
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
     * @return RepairOrder
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
    /**
     * @var string
     */
    private $deviceChangeImei = '';

    /**
     * @var string
     */
    private $deviceChangeBrand;

    /**
     * @var string
     */
    private $deviceChangeModel;


    /**
     * Set deviceChangeImei
     *
     * @param string $deviceChangeImei
     *
     * @return RepairOrder
     */
    public function setDeviceChangeImei($deviceChangeImei)
    {
        $this->deviceChangeImei = $deviceChangeImei;

        return $this;
    }

    /**
     * Get deviceChangeImei
     *
     * @return string
     */
    public function getDeviceChangeImei()
    {
        return $this->deviceChangeImei;
    }

    /**
     * Set deviceChangeBrand
     *
     * @param string $deviceChangeBrand
     *
     * @return RepairOrder
     */
    public function setDeviceChangeBrand($deviceChangeBrand)
    {
        $this->deviceChangeBrand = $deviceChangeBrand;

        return $this;
    }

    /**
     * Get deviceChangeBrand
     *
     * @return string
     */
    public function getDeviceChangeBrand()
    {
        return $this->deviceChangeBrand;
    }

    /**
     * Set deviceChangeModel
     *
     * @param string $deviceChangeModel
     *
     * @return RepairOrder
     */
    public function setDeviceChangeModel($deviceChangeModel)
    {
        $this->deviceChangeModel = $deviceChangeModel;

        return $this;
    }

    /**
     * Get deviceChangeModel
     *
     * @return string
     */
    public function getDeviceChangeModel()
    {
        return $this->deviceChangeModel;
    }
    /**
     * @var string
     */
    private $sketchpadData;


    /**
     * Set sketchpadData
     *
     * @param string $sketchpadData
     *
     * @return RepairOrder
     */
    public function setSketchpadData($sketchpadData)
    {
        $this->sketchpadData = $sketchpadData;

        return $this;
    }

    /**
     * Get sketchpadData
     *
     * @return string
     */
    public function getSketchpadData()
    {
        return $this->sketchpadData;
    }
    /**
     * @var string
     */
    private $phoneNumber;


    /**
     * Set phoneNumber
     *
     * @param string $phoneNumber
     *
     * @return RepairOrder
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }
    /**
     * @var integer
     */
    private $ticketNumber;


    /**
     * Set ticketNumber
     *
     * @param integer $ticketNumber
     *
     * @return RepairOrder
     */
    public function setTicketNumber($ticketNumber)
    {
        $this->ticketNumber = $ticketNumber;

        return $this;
    }

    /**
     * Get ticketNumber
     *
     * @return integer
     */
    public function getTicketNumber()
    {
        return $this->ticketNumber;
    }
    /**
     * @var string
     */
    private $guideIn = '';

    /**
     * @var string
     */
    private $guideOut = '';


    /**
     * Set guideIn
     *
     * @param string $guideIn
     *
     * @return RepairOrder
     */
    public function setGuideIn($guideIn)
    {
        $this->guideIn = $guideIn;

        return $this;
    }

    /**
     * Get guideIn
     *
     * @return string
     */
    public function getGuideIn()
    {
        return $this->guideIn;
    }

    /**
     * Set guideOut
     *
     * @param string $guideOut
     *
     * @return RepairOrder
     */
    public function setGuideOut($guideOut)
    {
        $this->guideOut = $guideOut;

        return $this;
    }

    /**
     * Get guideOut
     *
     * @return string
     */
    public function getGuideOut()
    {
        return $this->guideOut;
    }
    /**
     * @var \DateTime
     */
    private $deviceManufactureDate;


    /**
     * Set deviceManufactureDate
     *
     * @param \DateTime $deviceManufactureDate
     *
     * @return RepairOrder
     */
    public function setDeviceManufactureDate($deviceManufactureDate)
    {
        $this->deviceManufactureDate = $deviceManufactureDate;

        return $this;
    }

    /**
     * Get deviceManufactureDate
     *
     * @return \DateTime
     */
    public function getDeviceManufactureDate()
    {
        return $this->deviceManufactureDate;
    }
    /**
     * @var \Solucel\AdminBundle\Entity\Payer
     */
    private $payer;


    /**
     * Set payer
     *
     * @param \Solucel\AdminBundle\Entity\Payer $payer
     *
     * @return RepairOrder
     */
    public function setPayer(\Solucel\AdminBundle\Entity\Payer $payer = null)
    {
        $this->payer = $payer;

        return $this;
    }

    /**
     * Get payer
     *
     * @return \Solucel\AdminBundle\Entity\Payer
     */
    public function getPayer()
    {
        return $this->payer;
    }
    /**
     * @var string
     */
    private $deviceImei2 = '';

    /**
     * @var string
     */
    private $deviceImei2Out = '';


    /**
     * Set deviceImei2
     *
     * @param string $deviceImei2
     *
     * @return RepairOrder
     */
    public function setDeviceImei2($deviceImei2)
    {
        $this->deviceImei2 = $deviceImei2;

        return $this;
    }

    /**
     * Get deviceImei2
     *
     * @return string
     */
    public function getDeviceImei2()
    {
        return $this->deviceImei2;
    }

    /**
     * Set deviceImei2Out
     *
     * @param string $deviceImei2Out
     *
     * @return RepairOrder
     */
    public function setDeviceImei2Out($deviceImei2Out)
    {
        $this->deviceImei2Out = $deviceImei2Out;

        return $this;
    }

    /**
     * Get deviceImei2Out
     *
     * @return string
     */
    public function getDeviceImei2Out()
    {
        return $this->deviceImei2Out;
    }
    /**
     * @var string
     */
    private $deviceMsnOut;


    /**
     * Set deviceMsnOut
     *
     * @param string $deviceMsnOut
     *
     * @return RepairOrder
     */
    public function setDeviceMsnOut($deviceMsnOut)
    {
        $this->deviceMsnOut = $deviceMsnOut;

        return $this;
    }

    /**
     * Get deviceMsnOut
     *
     * @return string
     */
    public function getDeviceMsnOut()
    {
        return $this->deviceMsnOut;
    }
    /**
     * @var string
     */
    private $deviceImeiOut = '';


    /**
     * Set deviceImeiOut
     *
     * @param string $deviceImeiOut
     *
     * @return RepairOrder
     */
    public function setDeviceImeiOut($deviceImeiOut)
    {
        $this->deviceImeiOut = $deviceImeiOut;

        return $this;
    }

    /**
     * Get deviceImeiOut
     *
     * @return string
     */
    public function getDeviceImeiOut()
    {
        return $this->deviceImeiOut;
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
     * @return RepairOrder
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
	
    /**
     * @var boolean
     */
    private $warrantyFlag = true;


    /**
     * Set warrantyFlag
     *
     * @param boolean $warrantyFlag
     *
     * @return RepairOrder
     */
    public function setWarrantyFlag($warrantyFlag)
    {
        $this->warrantyFlag = $warrantyFlag;

        return $this;
    }

    /**
     * Get warrantyFlag
     *
     * @return boolean
     */
    public function getWarrantyFlag()
    {
        return $this->warrantyFlag;
    }
    /**
     * @var boolean
     */
    private $readyToAssign = false;


    /**
     * Set readyToAssign
     *
     * @param boolean $readyToAssign
     *
     * @return RepairOrder
     */
    public function setReadyToAssign($readyToAssign)
    {
        $this->readyToAssign = $readyToAssign;

        return $this;
    }

    /**
     * Get readyToAssign
     *
     * @return boolean
     */
    public function getReadyToAssign()
    {
        return $this->readyToAssign;
    }

    /**
     * @var string
     */
    private $paperworkComment = '';


    /**
     * Set paperworkComment
     *
     * @param string $paperworkComment
     *
     * @return RepairOrder
     */
    public function setPaperworkComment($paperworkComment)
    {
        $this->paperworkComment = $paperworkComment;

        return $this;
    }

    /**
     * Get paperworkComment
     *
     * @return string
     */
    public function getPaperworkComment()
    {
        return $this->paperworkComment;
    }
    /**
     * @var \Solucel\AdminBundle\Entity\RepairOrder
     */
    private $relapseRepairOrder;


    /**
     * Set relapseRepairOrder
     *
     * @param \Solucel\AdminBundle\Entity\RepairOrder $relapseRepairOrder
     *
     * @return RepairOrder
     */
    public function setRelapseRepairOrder(\Solucel\AdminBundle\Entity\RepairOrder $relapseRepairOrder = null)
    {
        $this->relapseRepairOrder = $relapseRepairOrder;

        return $this;
    }

    /**
     * Get relapseRepairOrder
     *
     * @return \Solucel\AdminBundle\Entity\RepairOrder
     */
    public function getRelapseRepairOrder()
    {
        return $this->relapseRepairOrder;
    }
    /**
     * @var boolean
     */
    private $oldData = false;


    /**
     * Set oldData
     *
     * @param boolean $oldData
     *
     * @return RepairOrder
     */
    public function setOldData($oldData)
    {
        $this->oldData = $oldData;

        return $this;
    }

    /**
     * Get oldData
     *
     * @return boolean
     */
    public function getOldData()
    {
        return $this->oldData;
    }
    /**
     * @var \Solucel\AdminBundle\Entity\OriginPoint
     */
    private $originPoint;


    /**
     * Set originPoint
     *
     * @param \Solucel\AdminBundle\Entity\OriginPoint $originPoint
     *
     * @return RepairOrder
     */
    public function setOriginPoint(\Solucel\AdminBundle\Entity\OriginPoint $originPoint = null)
    {
        $this->originPoint = $originPoint;

        return $this;
    }

    /**
     * Get originPoint
     *
     * @return \Solucel\AdminBundle\Entity\OriginPoint
     */
    public function getOriginPoint()
    {
        return $this->originPoint;
    }
}

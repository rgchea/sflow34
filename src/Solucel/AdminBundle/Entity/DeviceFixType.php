<?php

namespace Solucel\AdminBundle\Entity;

/**
 * DeviceFixType
 */
class DeviceFixType
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var \Solucel\AdminBundle\Entity\DeviceFixLevel
     */
    private $deviceFixLevel;


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
     * @return DeviceFixType
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
     * Set description
     *
     * @param string $description
     *
     * @return DeviceFixType
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set deviceFixLevel
     *
     * @param \Solucel\AdminBundle\Entity\DeviceFixLevel $deviceFixLevel
     *
     * @return DeviceFixType
     */
    public function setDeviceFixLevel(\Solucel\AdminBundle\Entity\DeviceFixLevel $deviceFixLevel = null)
    {
        $this->deviceFixLevel = $deviceFixLevel;

        return $this;
    }

    /**
     * Get deviceFixLevel
     *
     * @return \Solucel\AdminBundle\Entity\DeviceFixLevel
     */
    public function getDeviceFixLevel()
    {
        return $this->deviceFixLevel;
    }
	
	public function __toString(){
		return  $this->getName();
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
     * @return DeviceFixType
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
     * @var float
     */
    private $price = 0.0;

    /**
     * @var \Solucel\AdminBundle\Entity\DeviceBrand
     */
    private $deviceBrand;


    /**
     * Set price
     *
     * @param float $price
     *
     * @return DeviceFixType
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
     * Set deviceBrand
     *
     * @param \Solucel\AdminBundle\Entity\DeviceBrand $deviceBrand
     *
     * @return DeviceFixType
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
}

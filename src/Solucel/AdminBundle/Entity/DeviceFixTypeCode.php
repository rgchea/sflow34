<?php

namespace Solucel\AdminBundle\Entity;

/**
 * DeviceFixTypeCode
 */
class DeviceFixTypeCode
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $fixTypeCode = '';

    /**
     * @var \Solucel\AdminBundle\Entity\DeviceFixType
     */
    private $deviceFixType;

    /**
     * @var \Solucel\AdminBundle\Entity\DeviceBrand
     */
    private $deviceBrand;


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
     * Set fixTypeCode
     *
     * @param string $fixTypeCode
     *
     * @return DeviceFixTypeCode
     */
    public function setFixTypeCode($fixTypeCode)
    {
        $this->fixTypeCode = $fixTypeCode;

        return $this;
    }

    /**
     * Get fixTypeCode
     *
     * @return string
     */
    public function getFixTypeCode()
    {
        return $this->fixTypeCode;
    }

    /**
     * Set deviceFixType
     *
     * @param \Solucel\AdminBundle\Entity\DeviceFixType $deviceFixType
     *
     * @return DeviceFixTypeCode
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
     * Set deviceBrand
     *
     * @param \Solucel\AdminBundle\Entity\DeviceBrand $deviceBrand
     *
     * @return DeviceFixTypeCode
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
	
	public function __toString(){
		return  $this->getFixTypeCode();
	}		
}

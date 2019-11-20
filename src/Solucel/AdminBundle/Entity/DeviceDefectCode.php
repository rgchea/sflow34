<?php

namespace Solucel\AdminBundle\Entity;

/**
 * DeviceDefectCode
 */
class DeviceDefectCode
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $defectCode = '';

    /**
     * @var \Solucel\AdminBundle\Entity\DeviceDefect
     */
    private $deviceDefect;

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
     * Set defectCode
     *
     * @param string $defectCode
     *
     * @return DeviceDefectCode
     */
    public function setDefectCode($defectCode)
    {
        $this->defectCode = $defectCode;

        return $this;
    }

    /**
     * Get defectCode
     *
     * @return string
     */
    public function getDefectCode()
    {
        return $this->defectCode;
    }

    /**
     * Set deviceDefect
     *
     * @param \Solucel\AdminBundle\Entity\DeviceDefect $deviceDefect
     *
     * @return DeviceDefectCode
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

    /**
     * Set deviceBrand
     *
     * @param \Solucel\AdminBundle\Entity\DeviceBrand $deviceBrand
     *
     * @return DeviceDefectCode
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
		return $this->getDefectCode();
	}	
}

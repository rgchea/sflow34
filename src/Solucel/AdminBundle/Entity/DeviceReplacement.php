<?php

namespace Solucel\AdminBundle\Entity;

/**
 * DeviceReplacement
 */
class DeviceReplacement
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
     * @var string
     */
    private $description;


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
     * @return DeviceReplacement
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
     * @return DeviceReplacement
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
     * @var \Solucel\AdminBundle\Entity\DeviceReplacementType
     */
    private $deviceReplacementType;


    /**
     * Set deviceReplacementType
     *
     * @param \Solucel\AdminBundle\Entity\DeviceReplacementType $deviceReplacementType
     *
     * @return DeviceReplacement
     */
    public function setDeviceReplacementType(\Solucel\AdminBundle\Entity\DeviceReplacementType $deviceReplacementType = null)
    {
        $this->deviceReplacementType = $deviceReplacementType;

        return $this;
    }

    /**
     * Get deviceReplacementType
     *
     * @return \Solucel\AdminBundle\Entity\DeviceReplacementType
     */
    public function getDeviceReplacementType()
    {
        return $this->deviceReplacementType;
    }
	
	public function __toString(){
		return  $this->getName();
	}		
    /**
     * @var string
     */
    private $replacementCode = '0000';


    /**
     * Set replacementCode
     *
     * @param string $replacementCode
     *
     * @return DeviceReplacement
     */
    public function setReplacementCode($replacementCode)
    {
        $this->replacementCode = $replacementCode;

        return $this;
    }

    /**
     * Get replacementCode
     *
     * @return string
     */
    public function getReplacementCode()
    {
        return $this->replacementCode;
    }
    /**
     * @var \Solucel\AdminBundle\Entity\DeviceBrand
     */
    private $deviceBrand;

    /**
     * @var \Solucel\AdminBundle\Entity\DeviceModel
     */
    private $deviceModel;


    /**
     * Set deviceBrand
     *
     * @param \Solucel\AdminBundle\Entity\DeviceBrand $deviceBrand
     *
     * @return DeviceReplacement
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
     * @return DeviceReplacement
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
     * @var string
     */
    private $database = '';


    /**
     * Set database
     *
     * @param string $database
     *
     * @return DeviceReplacement
     */
    public function setDatabase($database)
    {
        $this->database = $database;

        return $this;
    }

    /**
     * Get database
     *
     * @return string
     */
    public function getDatabase()
    {
        return $this->database;
    }
    /**
     * @var string
     */
    private $strdatabase = '';


    /**
     * Set strdatabase
     *
     * @param string $strdatabase
     *
     * @return DeviceReplacement
     */
    public function setStrdatabase($strdatabase)
    {
        $this->strdatabase = $strdatabase;

        return $this;
    }

    /**
     * Get strdatabase
     *
     * @return string
     */
    public function getStrdatabase()
    {
        return $this->strdatabase;
    }
}

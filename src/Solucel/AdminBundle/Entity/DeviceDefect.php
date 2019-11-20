<?php

namespace Solucel\AdminBundle\Entity;

/**
 * DeviceDefect
 */
class DeviceDefect
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
     * @var \Solucel\AdminBundle\Entity\DeviceDefectType
     */
    private $deviceDefectType;


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
     * @return DeviceDefect
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
     * @param \Solucel\AdminBundle\Entity\DeviceDefectType $deviceDefectType
     *
     * @return DeviceDefect
     */
    public function setDeviceDefectType(\Solucel\AdminBundle\Entity\DeviceDefectType $deviceDefectType = null)
    {
        $this->deviceDefectType = $deviceDefectType;

        return $this;
    }

    /**
     * Get deviceDefectType
     *
     * @return \Solucel\AdminBundle\Entity\DeviceDefectType
     */
    public function getDeviceDefectType()
    {
        return $this->deviceDefectType;
    }
	
	public function __toString(){
		return $this->getName();
	}		
}

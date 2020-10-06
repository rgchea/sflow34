<?php

namespace Solucel\AdminBundle\Entity;

/**
 * DeviceModel
 */
class DeviceModel
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
     * @var boolean
     */
    private $isObsolete = false;

    /**
     * @var integer
     */
    private $style;

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
     * Set name
     *
     * @param string $name
     *
     * @return DeviceModel
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
     * Set isObsolete
     *
     * @param boolean $isObsolete
     *
     * @return DeviceModel
     */
    public function setIsObsolete($isObsolete)
    {
        $this->isObsolete = $isObsolete;

        return $this;
    }

    /**
     * Get isObsolete
     *
     * @return boolean
     */
    public function getIsObsolete()
    {
        return $this->isObsolete;
    }

    /**
     * Set style
     *
     * @param integer $style
     *
     * @return DeviceModel
     */
    public function setStyle($style)
    {
        $this->style = $style;

        return $this;
    }

    /**
     * Get style
     *
     * @return integer
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * Set deviceBrand
     *
     * @param \Solucel\AdminBundle\Entity\DeviceBrand $deviceBrand
     *
     * @return DeviceModel
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
		return  $this->getName();
	}		
    /**
     * @var string
     */
    private $productCodeIn = '';

    /**
     * @var string
     */
    private $productCodeOut = '';


    /**
     * Set productCodeIn
     *
     * @param string $productCodeIn
     *
     * @return DeviceModel
     */
    public function setProductCodeIn($productCodeIn)
    {
        $this->productCodeIn = $productCodeIn;

        return $this;
    }

    /**
     * Get productCodeIn
     *
     * @return string
     */
    public function getProductCodeIn()
    {
        return $this->productCodeIn;
    }

    /**
     * Set productCodeOut
     *
     * @param string $productCodeOut
     *
     * @return DeviceModel
     */
    public function setProductCodeOut($productCodeOut)
    {
        $this->productCodeOut = $productCodeOut;

        return $this;
    }

    /**
     * Get productCodeOut
     *
     * @return string
     */
    public function getProductCodeOut()
    {
        return $this->productCodeOut;
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
     * @return DeviceModel
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
}

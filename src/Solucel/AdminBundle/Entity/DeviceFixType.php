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
}

<?php

namespace Solucel\AdminBundle\Entity;

/**
 * Storehouse
 */
class Storehouse
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
     * @var boolean
     */
    private $enabled = true;

    /**
     * @var \DateTime
     */
    private $createdAt;

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
     * Set name
     *
     * @param string $name
     *
     * @return Storehouse
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
     * @return Storehouse
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
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return Storehouse
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Storehouse
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
     * Set serviceCenter
     *
     * @param \Solucel\AdminBundle\Entity\ServiceCenter $serviceCenter
     *
     * @return Storehouse
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
     * @var string
     */
    private $storehouseCode = '';


    /**
     * Set storehouseCode
     *
     * @param string $storehouseCode
     *
     * @return Storehouse
     */
    public function setStorehouseCode($storehouseCode)
    {
        $this->storehouseCode = $storehouseCode;

        return $this;
    }

    /**
     * Get storehouseCode
     *
     * @return string
     */
    public function getStorehouseCode()
    {
        return $this->storehouseCode;
    }
	
	public function __toString(){
		return $this->getName();
	}				
}

<?php

namespace Solucel\AdminBundle\Entity;

/**
 * ServiceCenter
 */
class ServiceCenter
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
     * @var \DateTime
     */
    private $createdAt = '0000-00-00 00:00:00';


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
     * @return ServiceCenter
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
     * @return ServiceCenter
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return ServiceCenter
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
     * @var boolean
     */
    private $enabled = true;


    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return ServiceCenter
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
	
	public function __toString(){
		$storeHouse = $this->getStorehouse();
		return $this->getName()."/ ".$storeHouse->getName()."-".$storeHouse->getStorehouseCode();
	}			
    /**
     * @var string
     */
    private $storehouse_id;

    /**
     * @var \Solucel\AdminBundle\Entity\Storehouse
     */
    private $storehouse;


    /**
     * Set storehouseId
     *
     * @param string $storehouseId
     *
     * @return ServiceCenter
     */
    public function setStorehouseId($storehouseId)
    {
        $this->storehouse_id = $storehouseId;

        return $this;
    }

    /**
     * Get storehouseId
     *
     * @return string
     */
    public function getStorehouseId()
    {
        return $this->storehouse_id;
    }

    /**
     * Set storehouse
     *
     * @param \Solucel\AdminBundle\Entity\Storehouse $storehouse
     *
     * @return ServiceCenter
     */
    public function setStorehouse(\Solucel\AdminBundle\Entity\Storehouse $storehouse = null)
    {
        $this->storehouse = $storehouse;

        return $this;
    }

    /**
     * Get storehouse
     *
     * @return \Solucel\AdminBundle\Entity\Storehouse
     */
    public function getStorehouse()
    {
        return $this->storehouse;
    }
}

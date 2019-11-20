<?php

namespace Solucel\AdminBundle\Entity;

/**
 * DeviceFixLevel
 */
class DeviceFixLevel
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $name;


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
     * @param integer $name
     *
     * @return DeviceFixLevel
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return integer
     */
    public function getName()
    {
        return $this->name;
    }
	
	public function __toString(){
		return  "".$this->getName();
	}		
	
}

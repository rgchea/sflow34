<?php

namespace Solucel\AdminBundle\Entity;

/**
 * DeviceReplacementType
 */
class DeviceReplacementType
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
     * @return DeviceReplacementType
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
		return  $this->getName();
	}	
    /**
     * @var string
     */
    private $code = '';


    /**
     * Set code
     *
     * @param string $code
     *
     * @return DeviceReplacementType
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }
}

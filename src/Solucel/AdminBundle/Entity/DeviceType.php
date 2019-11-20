<?php

namespace Solucel\AdminBundle\Entity;

/**
 * DeviceType
 */
class DeviceType
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
     * @return DeviceType
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
     * @return DeviceType
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

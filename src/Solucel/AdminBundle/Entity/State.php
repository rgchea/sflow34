<?php

namespace Solucel\AdminBundle\Entity;

/**
 * State
 */
class State
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
    private $zipCode = '';


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
     * @return State
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
     * Set zipCode
     *
     * @param string $zipCode
     *
     * @return State
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    /**
     * Get zipCode
     *
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }
	
	public function __toString(){
		return $this->getName();
	}		
    /**
     * @var \Solucel\AdminBundle\Entity\Country
     */
    private $country;


    /**
     * Set country
     *
     * @param \Solucel\AdminBundle\Entity\Country $country
     *
     * @return State
     */
    public function setCountry(\Solucel\AdminBundle\Entity\Country $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return \Solucel\AdminBundle\Entity\Country
     */
    public function getCountry()
    {
        return $this->country;
    }
}

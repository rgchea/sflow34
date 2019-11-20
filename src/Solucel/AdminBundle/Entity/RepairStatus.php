<?php

namespace Solucel\AdminBundle\Entity;

/**
 * RepairStatus
 */
class RepairStatus
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
     * @return RepairStatus
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
    private $repairStatusCode = '';


    /**
     * Set repairStatusCode
     *
     * @param string $repairStatusCode
     *
     * @return RepairStatus
     */
    public function setRepairStatusCode($repairStatusCode)
    {
        $this->repairStatusCode = $repairStatusCode;

        return $this;
    }

    /**
     * Get repairStatusCode
     *
     * @return string
     */
    public function getRepairStatusCode()
    {
        return $this->repairStatusCode;
    }
}

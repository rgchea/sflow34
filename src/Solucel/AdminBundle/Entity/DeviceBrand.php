<?php

namespace Solucel\AdminBundle\Entity;

/**
 * DeviceBrand
 */
class DeviceBrand
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
     * @return DeviceBrand
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
     * @return DeviceBrand
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
     * @var boolean
     */
    private $enabled = true;


    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return DeviceBrand
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
		return $this->getName();
	}	

    /**
     * @var integer
     */
    private $repairmentEstimatedHour = 0;


    /**
     * Set repairmentEstimatedHour
     *
     * @param integer $repairmentEstimatedHour
     *
     * @return DeviceBrand
     */
    public function setRepairmentEstimatedHour($repairmentEstimatedHour)
    {
        $this->repairmentEstimatedHour = $repairmentEstimatedHour;

        return $this;
    }

    /**
     * Get repairmentEstimatedHour
     *
     * @return integer
     */
    public function getRepairmentEstimatedHour()
    {
        return $this->repairmentEstimatedHour;
    }
    /**
     * @var integer
     */
    private $entryEstimatedTime = 0;


    /**
     * Set entryEstimatedTime
     *
     * @param integer $entryEstimatedTime
     *
     * @return DeviceBrand
     */
    public function setEntryEstimatedTime($entryEstimatedTime)
    {
        $this->entryEstimatedTime = $entryEstimatedTime;

        return $this;
    }

    /**
     * Get entryEstimatedTime
     *
     * @return integer
     */
    public function getEntryEstimatedTime()
    {
        return $this->entryEstimatedTime;
    }
}

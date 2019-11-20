<?php

namespace Solucel\AdminBundle\Entity;

/**
 * QualityControlSubGroup
 */
class QualityControlSubGroup
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
    private $enable;

    /**
     * @var \Solucel\AdminBundle\Entity\QualityControlGroup
     */
    private $qualityControlGroup;


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
     * @return QualityControlSubGroup
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
     * Set enable
     *
     * @param boolean $enable
     *
     * @return QualityControlSubGroup
     */
    public function setEnable($enable)
    {
        $this->enable = $enable;

        return $this;
    }

    /**
     * Get enable
     *
     * @return boolean
     */
    public function getEnable()
    {
        return $this->enable;
    }

    /**
     * Set qualityControlGroup
     *
     * @param \Solucel\AdminBundle\Entity\QualityControlGroup $qualityControlGroup
     *
     * @return QualityControlSubGroup
     */
    public function setQualityControlGroup(\Solucel\AdminBundle\Entity\QualityControlGroup $qualityControlGroup = null)
    {
        $this->qualityControlGroup = $qualityControlGroup;

        return $this;
    }

    /**
     * Get qualityControlGroup
     *
     * @return \Solucel\AdminBundle\Entity\QualityControlGroup
     */
    public function getQualityControlGroup()
    {
        return $this->qualityControlGroup;
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
     * @return QualityControlSubGroup
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
	
}

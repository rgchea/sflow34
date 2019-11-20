<?php

namespace Solucel\AdminBundle\Entity;

/**
 * RepairOrderQualityControlRegister
 */
class RepairOrderQualityControlRegister
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $technicianCheck = 'NO PASO';

    /**
     * @var string
     */
    private $qualityCheck = 'NO PASO';

    /**
     * @var \Solucel\AdminBundle\Entity\RepairOrderQualityControl
     */
    private $repairOrderQualityControl;

    /**
     * @var \Solucel\AdminBundle\Entity\QualityControlGroup
     */
    private $qualityControlGroup;

    /**
     * @var \Solucel\AdminBundle\Entity\QualityControlSubGroup
     */
    private $qualityControlSubGroup;


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
     * Set technicianCheck
     *
     * @param string $technicianCheck
     *
     * @return RepairOrderQualityControlRegister
     */
    public function setTechnicianCheck($technicianCheck)
    {
        $this->technicianCheck = $technicianCheck;

        return $this;
    }

    /**
     * Get technicianCheck
     *
     * @return string
     */
    public function getTechnicianCheck()
    {
        return $this->technicianCheck;
    }

    /**
     * Set qualityCheck
     *
     * @param string $qualityCheck
     *
     * @return RepairOrderQualityControlRegister
     */
    public function setQualityCheck($qualityCheck)
    {
        $this->qualityCheck = $qualityCheck;

        return $this;
    }

    /**
     * Get qualityCheck
     *
     * @return string
     */
    public function getQualityCheck()
    {
        return $this->qualityCheck;
    }

    /**
     * Set repairOrderQualityControl
     *
     * @param \Solucel\AdminBundle\Entity\RepairOrderQualityControl $repairOrderQualityControl
     *
     * @return RepairOrderQualityControlRegister
     */
    public function setRepairOrderQualityControl(\Solucel\AdminBundle\Entity\RepairOrderQualityControl $repairOrderQualityControl = null)
    {
        $this->repairOrderQualityControl = $repairOrderQualityControl;

        return $this;
    }

    /**
     * Get repairOrderQualityControl
     *
     * @return \Solucel\AdminBundle\Entity\RepairOrderQualityControl
     */
    public function getRepairOrderQualityControl()
    {
        return $this->repairOrderQualityControl;
    }

    /**
     * Set qualityControlGroup
     *
     * @param \Solucel\AdminBundle\Entity\QualityControlGroup $qualityControlGroup
     *
     * @return RepairOrderQualityControlRegister
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
     * Set qualityControlSubGroup
     *
     * @param \Solucel\AdminBundle\Entity\QualityControlSubGroup $qualityControlSubGroup
     *
     * @return RepairOrderQualityControlRegister
     */
    public function setQualityControlSubGroup(\Solucel\AdminBundle\Entity\QualityControlSubGroup $qualityControlSubGroup = null)
    {
        $this->qualityControlSubGroup = $qualityControlSubGroup;

        return $this;
    }

    /**
     * Get qualityControlSubGroup
     *
     * @return \Solucel\AdminBundle\Entity\QualityControlSubGroup
     */
    public function getQualityControlSubGroup()
    {
        return $this->qualityControlSubGroup;
    }
    /**
     * @var boolean
     */
    private $notApply = false;


    /**
     * Set notApply
     *
     * @param boolean $notApply
     *
     * @return RepairOrderQualityControlRegister
     */
    public function setNotApply($notApply)
    {
        $this->notApply = $notApply;

        return $this;
    }

    /**
     * Get notApply
     *
     * @return boolean
     */
    public function getNotApply()
    {
        return $this->notApply;
    }
    /**
     * @var boolean
     */
    private $technicianUncheck = false;

    /**
     * @var boolean
     */
    private $qualityUncheck = false;


    /**
     * Set technicianUncheck
     *
     * @param boolean $technicianUncheck
     *
     * @return RepairOrderQualityControlRegister
     */
    public function setTechnicianUncheck($technicianUncheck)
    {
        $this->technicianUncheck = $technicianUncheck;

        return $this;
    }

    /**
     * Get technicianUncheck
     *
     * @return boolean
     */
    public function getTechnicianUncheck()
    {
        return $this->technicianUncheck;
    }

    /**
     * Set qualityUncheck
     *
     * @param boolean $qualityUncheck
     *
     * @return RepairOrderQualityControlRegister
     */
    public function setQualityUncheck($qualityUncheck)
    {
        $this->qualityUncheck = $qualityUncheck;

        return $this;
    }

    /**
     * Get qualityUncheck
     *
     * @return boolean
     */
    public function getQualityUncheck()
    {
        return $this->qualityUncheck;
    }
}

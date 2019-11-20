<?php

namespace Solucel\AdminBundle\Entity;

/**
 * RepairOrderQualityControl
 */
class RepairOrderQualityControl
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $version = '1';

    /**
     * @var string
     */
    private $technicianCheck = 'NO PASO';

    /**
     * @var string
     */
    private $qualityCheck = 'NO PASO';

    /**
     * @var \Solucel\AdminBundle\Entity\User
     */
    private $createdBy;

    /**
     * @var \Solucel\AdminBundle\Entity\RepairOrder
     */
    private $repairOrder;

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
     * Set version
     *
     * @param integer $version
     *
     * @return RepairOrderQualityControl
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get version
     *
     * @return integer
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set technicianCheck
     *
     * @param string $technicianCheck
     *
     * @return RepairOrderQualityControl
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
     * @return RepairOrderQualityControl
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
     * Set createdBy
     *
     * @param \Solucel\AdminBundle\Entity\User $createdBy
     *
     * @return RepairOrderQualityControl
     */
    public function setCreatedBy(\Solucel\AdminBundle\Entity\User $createdBy = null)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return \Solucel\AdminBundle\Entity\User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set repairOrder
     *
     * @param \Solucel\AdminBundle\Entity\RepairOrder $repairOrder
     *
     * @return RepairOrderQualityControl
     */
    public function setRepairOrder(\Solucel\AdminBundle\Entity\RepairOrder $repairOrder = null)
    {
        $this->repairOrder = $repairOrder;

        return $this;
    }

    /**
     * Get repairOrder
     *
     * @return \Solucel\AdminBundle\Entity\RepairOrder
     */
    public function getRepairOrder()
    {
        return $this->repairOrder;
    }

    /**
     * Set qualityControlSubGroup
     *
     * @param \Solucel\AdminBundle\Entity\QualityControlSubGroup $qualityControlSubGroup
     *
     * @return RepairOrderQualityControl
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
    private $qualityApproved = true;


    /**
     * Set qualityApproved
     *
     * @param boolean $qualityApproved
     *
     * @return RepairOrderQualityControl
     */
    public function setQualityApproved($qualityApproved)
    {
        $this->qualityApproved = $qualityApproved;

        return $this;
    }

    /**
     * Get qualityApproved
     *
     * @return boolean
     */
    public function getQualityApproved()
    {
        return $this->qualityApproved;
    }
    /**
     * @var string
     */
    private $techComment;

    /**
     * @var string
     */
    private $qaComment;


    /**
     * Set techComment
     *
     * @param string $techComment
     *
     * @return RepairOrderQualityControl
     */
    public function setTechComment($techComment)
    {
        $this->techComment = $techComment;

        return $this;
    }

    /**
     * Get techComment
     *
     * @return string
     */
    public function getTechComment()
    {
        return $this->techComment;
    }

    /**
     * Set qaComment
     *
     * @param string $qaComment
     *
     * @return RepairOrderQualityControl
     */
    public function setQaComment($qaComment)
    {
        $this->qaComment = $qaComment;

        return $this;
    }

    /**
     * Get qaComment
     *
     * @return string
     */
    public function getQaComment()
    {
        return $this->qaComment;
    }
    /**
     * @var \DateTime
     */
    private $createdAt;


    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return RepairOrderQualityControl
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
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
	
    public function setCreatedAtValue()
    {
        // Add your code here
        $this->createdAt = new \DateTime();
	
    }	
	
}

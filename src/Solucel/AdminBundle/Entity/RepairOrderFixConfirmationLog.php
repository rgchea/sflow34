<?php

namespace Solucel\AdminBundle\Entity;

/**
 * RepairOrderFixConfirmationLog
 */
class RepairOrderFixConfirmationLog
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $log_comment;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \Solucel\AdminBundle\Entity\User
     */
    private $createdBy;

    /**
     * @var \Solucel\AdminBundle\Entity\RepairOrderFix
     */
    private $repairOrderFix;


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
     * Set logComment
     *
     * @param string $logComment
     *
     * @return RepairOrderFixConfirmationLog
     */
    public function setLogComment($logComment)
    {
        $this->log_comment = $logComment;

        return $this;
    }

    /**
     * Get logComment
     *
     * @return string
     */
    public function getLogComment()
    {
        return $this->log_comment;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return RepairOrderFixConfirmationLog
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
     * Set createdBy
     *
     * @param \Solucel\AdminBundle\Entity\User $createdBy
     *
     * @return RepairOrderFixConfirmationLog
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
     * Set repairOrderFix
     *
     * @param \Solucel\AdminBundle\Entity\RepairOrderFix $repairOrderFix
     *
     * @return RepairOrderFixConfirmationLog
     */
    public function setRepairOrderFix(\Solucel\AdminBundle\Entity\RepairOrderFix $repairOrderFix = null)
    {
        $this->repairOrderFix = $repairOrderFix;

        return $this;
    }

    /**
     * Get repairOrderFix
     *
     * @return \Solucel\AdminBundle\Entity\RepairOrderFix
     */
    public function getRepairOrderFix()
    {
        return $this->repairOrderFix;
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
     * @return RepairOrderFixConfirmationLog
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
    /**
     * @var string
     */
    private $logComment;


    /**
     * @var boolean
     */
    private $clientConfirmation = true;


    /**
     * Set clientConfirmation
     *
     * @param boolean $clientConfirmation
     *
     * @return RepairOrderFixConfirmationLog
     */
    public function setClientConfirmation($clientConfirmation)
    {
        $this->clientConfirmation = $clientConfirmation;

        return $this;
    }

    /**
     * Get clientConfirmation
     *
     * @return boolean
     */
    public function getClientConfirmation()
    {
        return $this->clientConfirmation;
    }
}

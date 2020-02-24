<?php

namespace Solucel\AdminBundle\Entity;

/**
 * Agency
 */
class Agency
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
     * @var boolean
     */
    private $enabled = true;

    /**
     * @var \DateTime
     */
    private $createdAt;


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
     * @return Agency
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
     * @return Agency
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
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return Agency
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Agency
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
	
	public function __toString(){
		return $this->getName();
	}	
    /**
     * @var \Solucel\AdminBundle\Entity\Operator
     */
    private $operator;


    /**
     * Set operator
     *
     * @param \Solucel\AdminBundle\Entity\Operator $operator
     *
     * @return Agency
     */
    public function setOperator(\Solucel\AdminBundle\Entity\Operator $operator = null)
    {
        $this->operator = $operator;

        return $this;
    }

    /**
     * Get operator
     *
     * @return \Solucel\AdminBundle\Entity\Operator
     */
    public function getOperator()
    {
        return $this->operator;
    }
    /**
     * @var \Solucel\AdminBundle\Entity\User
     */
    private $updatedBy;


    /**
     * Set updatedBy
     *
     * @param \Solucel\AdminBundle\Entity\User $updatedBy
     *
     * @return Agency
     */
    public function setUpdatedBy(\Solucel\AdminBundle\Entity\User $updatedBy = null)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * Get updatedBy
     *
     * @return \Solucel\AdminBundle\Entity\User
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }
}

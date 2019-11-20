<?php

namespace Solucel\AdminBundle\Entity;

/**
 * Role
 */
class Role
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
     * @var \Solucel\AdminBundle\Entity\CompanyDepartment
     */
    private $companyDepartment;


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
     * @return Role
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
     * Set companyDepartment
     *
     * @param \Solucel\AdminBundle\Entity\CompanyDepartment $companyDepartment
     *
     * @return Role
     */
    public function setCompanyDepartment(\Solucel\AdminBundle\Entity\CompanyDepartment $companyDepartment = null)
    {
        $this->companyDepartment = $companyDepartment;

        return $this;
    }

    /**
     * Get companyDepartment
     *
     * @return \Solucel\AdminBundle\Entity\CompanyDepartment
     */
    public function getCompanyDepartment()
    {
        return $this->companyDepartment;
    }
	
	public function __toString(){
		return $this->getName();
	}		
}

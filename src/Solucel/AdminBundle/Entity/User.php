<?php
// src/AppBundle/Entity/User.php

namespace Solucel\AdminBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }
    
    /**
     * @var string
     */
    private $name = '';

    /**
     * @var string
     */
    private $lastName = '';

    /**
     * @var \Solucel\AdminBundle\Entity\Role
     */
    private $role;


    /**
     * Set name
     *
     * @param string $name
     *
     * @return User
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
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set role
     *
     * @param \Solucel\AdminBundle\Entity\Role $role
     *
     * @return User
     */
    public function setRole(\Solucel\AdminBundle\Entity\Role $role = null)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return \Solucel\AdminBundle\Entity\Role
     */
    public function getRole()
    {
        return $this->role;
    }
	
	public function __toString(){
		return $this->getName()." ".$this->getLastName();
	}	
	
    /**
     * @var \Solucel\AdminBundle\Entity\ServiceCenter
     */
    private $serviceCenter;


    /**
     * Set serviceCenter
     *
     * @param \Solucel\AdminBundle\Entity\ServiceCenter $serviceCenter
     *
     * @return User
     */
    public function setServiceCenter(\Solucel\AdminBundle\Entity\ServiceCenter $serviceCenter = null)
    {
        $this->serviceCenter = $serviceCenter;

        return $this;
    }

    /**
     * Get serviceCenter
     *
     * @return \Solucel\AdminBundle\Entity\ServiceCenter
     */
    public function getServiceCenter()
    {
        return $this->serviceCenter;
    }
    /**
     * @var \Solucel\AdminBundle\Entity\DeviceBrand
     */
    private $deviceBrand;

    /**
     * @var \Solucel\AdminBundle\Entity\Operator
     */
    private $operator;


    /**
     * Set deviceBrand
     *
     * @param \Solucel\AdminBundle\Entity\DeviceBrand $deviceBrand
     *
     * @return User
     */
    public function setDeviceBrand(\Solucel\AdminBundle\Entity\DeviceBrand $deviceBrand = null)
    {
        $this->deviceBrand = $deviceBrand;

        return $this;
    }

    /**
     * Get deviceBrand
     *
     * @return \Solucel\AdminBundle\Entity\DeviceBrand
     */
    public function getDeviceBrand()
    {
        return $this->deviceBrand;
    }

    /**
     * Set operator
     *
     * @param \Solucel\AdminBundle\Entity\Operator $operator
     *
     * @return User
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
}

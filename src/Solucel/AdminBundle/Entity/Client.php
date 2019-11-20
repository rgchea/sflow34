<?php

namespace Solucel\AdminBundle\Entity;

/**
 * Client
 */
class Client
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
    private $lastName = '';

    /**
     * @var integer
     */
    private $dpi = '0';

    /**
     * @var integer
     */
    private $phone;

    /**
     * @var integer
     */
    private $contactPhone = '0';

    /**
     * @var integer
     */
    private $contactPhoneOther = '0';

    /**
     * @var boolean
     */
    private $enable = '1';

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var string
     */
    private $clientType;


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
     * @return Client
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
     * @return Client
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
     * Set dpi
     *
     * @param integer $dpi
     *
     * @return Client
     */
    public function setDpi($dpi)
    {
        $this->dpi = $dpi;

        return $this;
    }

    /**
     * Get dpi
     *
     * @return integer
     */
    public function getDpi()
    {
        return $this->dpi;
    }

    /**
     * Set phone
     *
     * @param integer $phone
     *
     * @return Client
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return integer
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set contactPhone
     *
     * @param integer $contactPhone
     *
     * @return Client
     */
    public function setContactPhone($contactPhone)
    {
        $this->contactPhone = $contactPhone;

        return $this;
    }

    /**
     * Get contactPhone
     *
     * @return integer
     */
    public function getContactPhone()
    {
        return $this->contactPhone;
    }

    /**
     * Set contactPhoneOther
     *
     * @param integer $contactPhoneOther
     *
     * @return Client
     */
    public function setContactPhoneOther($contactPhoneOther)
    {
        $this->contactPhoneOther = $contactPhoneOther;

        return $this;
    }

    /**
     * Get contactPhoneOther
     *
     * @return integer
     */
    public function getContactPhoneOther()
    {
        return $this->contactPhoneOther;
    }

    /**
     * Set enable
     *
     * @param boolean $enable
     *
     * @return Client
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Client
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
     * Set clientType
     *
     * @param string $clientType
     *
     * @return Client
     */
    public function setClientType($clientType)
    {
        $this->clientType = $clientType;

        return $this;
    }

    /**
     * Get clientType
     *
     * @return string
     */
    public function getClientType()
    {
        return $this->clientType;
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
     * @return Client
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
		return $this->getName()." ".$this->getLastName();
	}	
	
    /**
     * @var integer
     */
    private $nit = '0';


    /**
     * Set nit
     *
     * @param integer $nit
     *
     * @return Client
     */
    public function setNit($nit)
    {
        $this->nit = $nit;

        return $this;
    }

    /**
     * Get nit
     *
     * @return integer
     */
    public function getNit()
    {
        return $this->nit;
    }
    /**
     * @var string
     */
    private $email = '';


    /**
     * Set email
     *
     * @param string $email
     *
     * @return Client
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
    /**
     * @var string
     */
    private $clientCode = '0000';


    /**
     * Set clientCode
     *
     * @param string $clientCode
     *
     * @return Client
     */
    public function setClientCode($clientCode)
    {
        $this->clientCode = $clientCode;

        return $this;
    }

    /**
     * Get clientCode
     *
     * @return string
     */
    public function getClientCode()
    {
        return $this->clientCode;
    }
    /**
     * @var \Solucel\AdminBundle\Entity\State
     */
    private $state;


    /**
     * Set state
     *
     * @param \Solucel\AdminBundle\Entity\State $state
     *
     * @return Client
     */
    public function setState(\Solucel\AdminBundle\Entity\State $state = null)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return \Solucel\AdminBundle\Entity\State
     */
    public function getState()
    {
        return $this->state;
    }
}

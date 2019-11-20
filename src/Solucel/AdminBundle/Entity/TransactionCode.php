<?php

namespace Solucel\AdminBundle\Entity;

/**
 * TransactionCode
 */
class TransactionCode
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $description = '';

    /**
     * @var string
     */
    private $transactionCode = '';

    /**
     * @var string
     */
    private $serviceRepair = '';


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
     * Set description
     *
     * @param string $description
     *
     * @return TransactionCode
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
     * Set transactionCode
     *
     * @param string $transactionCode
     *
     * @return TransactionCode
     */
    public function setTransactionCode($transactionCode)
    {
        $this->transactionCode = $transactionCode;

        return $this;
    }

    /**
     * Get transactionCode
     *
     * @return string
     */
    public function getTransactionCode()
    {
        return $this->transactionCode;
    }

    /**
     * Set serviceRepair
     *
     * @param string $serviceRepair
     *
     * @return TransactionCode
     */
    public function setServiceRepair($serviceRepair)
    {
        $this->serviceRepair = $serviceRepair;

        return $this;
    }

    /**
     * Get serviceRepair
     *
     * @return string
     */
    public function getServiceRepair()
    {
        return $this->serviceRepair;
    }
	
	public function __toString(){
		return $this->getTransactionCode();
	}		
}

<?php

namespace Solucel\AdminBundle\Entity;

/**
 * RepairOrderPhoto
 */
class RepairOrderPhoto
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
    private $photoPath = '';

    /**
     * @var string
     */
    private $photoType = 'Otro';

    /**
     * @var \Solucel\AdminBundle\Entity\RepairOrder
     */
    private $repairOrder;


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
     * @return RepairOrderPhoto
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
     * Set photoPath
     *
     * @param string $photoPath
     *
     * @return RepairOrderPhoto
     */
    public function setPhotoPath($photoPath)
    {
        $this->photoPath = $photoPath;

        return $this;
    }

    /**
     * Get photoPath
     *
     * @return string
     */
    public function getPhotoPath()
    {
        return $this->photoPath;
    }

    /**
     * Set photoType
     *
     * @param string $photoType
     *
     * @return RepairOrderPhoto
     */
    public function setPhotoType($photoType)
    {
        $this->photoType = $photoType;

        return $this;
    }

    /**
     * Get photoType
     *
     * @return string
     */
    public function getPhotoType()
    {
        return $this->photoType;
    }

    /**
     * Set repairOrder
     *
     * @param \Solucel\AdminBundle\Entity\RepairOrder $repairOrder
     *
     * @return RepairOrderPhoto
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
}

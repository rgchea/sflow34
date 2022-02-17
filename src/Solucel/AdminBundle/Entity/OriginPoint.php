<?php

namespace Solucel\AdminBundle\Entity;

/**
 * OriginPoint
 */
class OriginPoint
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
     * @return OriginPoint
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

    public function __toString(){
        return $this->getName();
    }

}

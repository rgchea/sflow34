<?php

namespace Solucel\AdminBundle\Entity;

/**
 * Module
 */
class Module
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
    private $systemName = '';

    /**
     * @var string
     */
    private $systemRoute = '';

    /**
     * @var string
     */
    private $menuType = 'menu';

    /**
     * @var integer
     */
    private $menuOrder = '0';

    /**
     * @var \Solucel\AdminBundle\Entity\Module
     */
    private $parentModule;


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
     * @return Module
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
     * Set systemName
     *
     * @param string $systemName
     *
     * @return Module
     */
    public function setSystemName($systemName)
    {
        $this->systemName = $systemName;

        return $this;
    }

    /**
     * Get systemName
     *
     * @return string
     */
    public function getSystemName()
    {
        return $this->systemName;
    }

    /**
     * Set systemRoute
     *
     * @param string $systemRoute
     *
     * @return Module
     */
    public function setSystemRoute($systemRoute)
    {
        $this->systemRoute = $systemRoute;

        return $this;
    }

    /**
     * Get systemRoute
     *
     * @return string
     */
    public function getSystemRoute()
    {
        return $this->systemRoute;
    }

    /**
     * Set menuType
     *
     * @param string $menuType
     *
     * @return Module
     */
    public function setMenuType($menuType)
    {
        $this->menuType = $menuType;

        return $this;
    }

    /**
     * Get menuType
     *
     * @return string
     */
    public function getMenuType()
    {
        return $this->menuType;
    }

    /**
     * Set menuOrder
     *
     * @param integer $menuOrder
     *
     * @return Module
     */
    public function setMenuOrder($menuOrder)
    {
        $this->menuOrder = $menuOrder;

        return $this;
    }

    /**
     * Get menuOrder
     *
     * @return integer
     */
    public function getMenuOrder()
    {
        return $this->menuOrder;
    }

    /**
     * Set parentModule
     *
     * @param \Solucel\AdminBundle\Entity\Module $parentModule
     *
     * @return Module
     */
    public function setParentModule(\Solucel\AdminBundle\Entity\Module $parentModule = null)
    {
        $this->parentModule = $parentModule;

        return $this;
    }

    /**
     * Get parentModule
     *
     * @return \Solucel\AdminBundle\Entity\Module
     */
    public function getParentModule()
    {
        return $this->parentModule;
    }
    /**
     * @var boolean
     */
    private $visible = true;


    /**
     * Set visible
     *
     * @param boolean $visible
     *
     * @return Module
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Get visible
     *
     * @return boolean
     */
    public function getVisible()
    {
        return $this->visible;
    }
}

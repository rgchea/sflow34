<?php

namespace Solucel\AdminBundle\Entity;

/**
 * ModuleAccess
 */
class ModuleAccess
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var boolean
     */
    private $canRead = '1';

    /**
     * @var boolean
     */
    private $canWrite = '1';

    /**
     * @var \Solucel\AdminBundle\Entity\Module
     */
    private $module;

    /**
     * @var \Solucel\AdminBundle\Entity\Role
     */
    private $role;


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
     * Set canRead
     *
     * @param boolean $canRead
     *
     * @return ModuleAccess
     */
    public function setCanRead($canRead)
    {
        $this->canRead = $canRead;

        return $this;
    }

    /**
     * Get canRead
     *
     * @return boolean
     */
    public function getCanRead()
    {
        return $this->canRead;
    }

    /**
     * Set canWrite
     *
     * @param boolean $canWrite
     *
     * @return ModuleAccess
     */
    public function setCanWrite($canWrite)
    {
        $this->canWrite = $canWrite;

        return $this;
    }

    /**
     * Get canWrite
     *
     * @return boolean
     */
    public function getCanWrite()
    {
        return $this->canWrite;
    }

    /**
     * Set module
     *
     * @param \Solucel\AdminBundle\Entity\Module $module
     *
     * @return ModuleAccess
     */
    public function setModule(\Solucel\AdminBundle\Entity\Module $module = null)
    {
        $this->module = $module;

        return $this;
    }

    /**
     * Get module
     *
     * @return \Solucel\AdminBundle\Entity\Module
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Set role
     *
     * @param \Solucel\AdminBundle\Entity\Role $role
     *
     * @return ModuleAccess
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
}

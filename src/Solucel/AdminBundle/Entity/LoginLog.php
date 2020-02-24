<?php

namespace Solucel\AdminBundle\Entity;

/**
 * LoginLog
 */
class LoginLog
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $action = '';

    /**
     * @var \DateTime
     */
    private $createdAt = '2001-01-01 00:00:00';

    /**
     * @var \Solucel\AdminBundle\Entity\User
     */
    private $user;


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
     * Set action
     *
     * @param string $action
     *
     * @return LoginLog
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return LoginLog
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
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
     * Set user
     *
     * @param \Solucel\AdminBundle\Entity\User $user
     *
     * @return LoginLog
     */
    public function setUser(\Solucel\AdminBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Solucel\AdminBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}

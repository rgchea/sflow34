<?php

namespace Solucel\MyFOSUserBundle\EventListener;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Doctrine\ORM\EntityManagerInterface;

use Solucel\AdminBundle\Entity\LoginLog;

use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Custom login listener.
 */
class LoginListener
{
    /** @var \Symfony\Component\Security\Core\Security */
    private $security;

    /** @var \Doctrine\ORM\EntityManager */
    private $em;

    private $container;

    private $doc;

    /**
     * Constructor
     * 
     * @param SecurityContext $security
     * @param Doctrine        $doctrine
     */
    //public function __construct(Security $security, Doctrine $doctrine, Container $container)
    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        $this->securityContext = $security;
        //$this->doc = $doctrine;
        //$this->em              = $doctrine->getManager();
        $this->em = $entityManager;

    }

    /**
     * Do the magic.
     * 
     * @param InteractiveLoginEvent $event
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {

		$session = new Session();
		
        if ($this->securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
            // user has just logged in
        }

        if ($this->securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // user has logged in using remember_me cookie
        }

        // First get that user object so we can work with it
        $user = $event->getAuthenticationToken()->getUser();
		
		//print "yo el chapulin colorado";die;
        $objLogin = new LoginLog();
        $objLogin->setAction("login");
        $objLogin->setUser($user);
        $objLogin->setCreatedAt(new \DateTime("now"));
			
        $this->em->persist($objLogin);
        $this->em->flush();


    }
}

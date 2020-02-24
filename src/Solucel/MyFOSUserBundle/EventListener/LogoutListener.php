<?php

// LogoutListener.php - Change the namespace according to the location of this class in your bundle
namespace Solucel\MyFOSUserBundle\EventListener;

use Solucel\AdminBundle\Entity\LoginLog;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;
use FOS\UserBundle\Model\UserManagerInterface;

use Symfony\Component\Security\Core\Security;
use Psr\Container\ContainerInterface;
use Doctrine\ORM\EntityManagerInterface;


class LogoutListener implements LogoutHandlerInterface {

    /** @var \Symfony\Component\Security\Core\SecurityContext */
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
    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->em              = $entityManager;

    }

    public function logout(Request $Request, Response $Response, TokenInterface $Token) {

        // Your handling here
        //$user = $this->container->get('security.context')->getToken()->getUser();
        $user = $this->security->getUser();
        //$user = $event->getAuthenticationToken()->getUser();
        //var_dump($user);die;

        $objLogin = new LoginLog();
        $objLogin->setAction("logout");
        $objLogin->setUser($user);
        $objLogin->setCreatedAt(new \DateTime("now"));

        $this->em->persist($objLogin);
        $this->em->flush();
    }
}
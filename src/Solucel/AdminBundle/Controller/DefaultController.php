<?php

namespace Solucel\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Session;


class DefaultController extends Controller
{
	

	
		
    public function indexAction()
    {
    	
		$this->get("services")->setVars('dashboard');
		
		//print date_format(date_create('2018-10-01 10:10:10'), 'c');die;	
		//print "<pre>";var_dump($session->get("user_access"));die;
		
        return $this->render('SolucelAdminBundle:Default:index.html.twig');
    }
	
    public function menuAction(){
    	$session = new Session();
    	$item    = $session->get('item');

		//print "<pre>";
		//var_dump($session->get("user_access"));die;
		
    	return $this->render('SolucelAdminBundle:Partials:menu.html.twig', 
    							array('item' => $item, 'user_access' => $session->get("user_access"))
							);
		
    }
	
		
}

<?php

namespace Solucel\MyFOSUserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SolucelMyFOSUserBundle:Default:index.html.twig', array('name' => $name));
    }
	
	public function pageNotFoundAction()
    {
        //throw new NotFoundHttpException();
        return $this->render('SolucelMyFOSUserBundle:Exception:error404.html.twig');
    }
}

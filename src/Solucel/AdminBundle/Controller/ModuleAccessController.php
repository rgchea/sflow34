<?php

namespace Solucel\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Solucel\AdminBundle\Entity\ModuleAccess;
use Solucel\AdminBundle\Form\ModuleAccessType;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


/**
 * ModuleAccess controller.
 *
 */
class ModuleAccessController extends Controller
{


    /**
     * Lists all ModuleAccess entities.
     *
     */
    public function indexAction()
    {
    	
		$this->get("services")->setVars('moduleAccess');
        $em = $this->getDoctrine()->getManager();
		$session = new Session();
		$roleID = $session->get('userLogged')->getRole()->getId();
		 
		//var_dump($roleID);die;

        //$entitiesAccess = $em->getRepository('SolucelAdminBundle:ModuleAccess')->findBy(array('role' => $roleID));
        $entitiesModule = $em->getRepository('SolucelAdminBundle:ModuleAccess')->getModules();
        $entitiesAccess = $em->getRepository('SolucelAdminBundle:ModuleAccess')->findAll();
		//var_dump($entitiesModule);die;
		$entitiesRoles = $em->getRepository('SolucelAdminBundle:Role')->findAll();
 

        return $this->render('SolucelAdminBundle:ModuleAccess:index.html.twig', array(
            'entitiesAccess' => $entitiesAccess,
            'entitiesModule' => $entitiesModule,
            'entitiesRole' => $entitiesRoles,
        ));
    }
    /**
     * Creates a new ModuleAccess entity.
     *
     */
    public function createAction(Request $request)
    {
    	$this->get("services")->setVars('moduleAccess');
		$session = new Session();
		
	    $em = $this->getDoctrine()->getManager();    	
		
		unset($_REQUEST["PHPSESSID"]);
		unset($_REQUEST["REMEMBERME"]);
		//print "<pre>";
		//var_dump($_REQUEST);die;
		$roleID = $session->get('userLogged')->getRole()->getId();
		//var_dump($roleID);die;
		
		$clean = $em->getRepository('SolucelAdminBundle:ModuleAccess')->cleanRoleAccess(0);
		
		foreach ($_REQUEST as $key => $value) {
			$splitKey = explode("_", $key);
			$roleID = $splitKey[0]; 
			$moduleID = $splitKey[1];
			
	        $entity = new ModuleAccess();
			
			
			$module = $em->getRepository('SolucelAdminBundle:Module')->find($moduleID);
			$entity->setModule($module);
			$role = $em->getRepository('SolucelAdminBundle:Role')->find($roleID);
			$entity->setRole($role);
			

	        $em->persist($entity);
	        $em->flush();
			
			
		}

		$this->get('services')->flashSuccess($request);
        return $this->redirect($this->generateUrl('solucel_admin_moduleaccess'));

    }


}

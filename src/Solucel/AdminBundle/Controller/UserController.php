<?php

namespace Solucel\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Session;


use Solucel\AdminBundle\Entity\User;
use Solucel\AdminBundle\Form\UserType;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * User controller.
 *
 */

//todo: new ajax list



class UserController extends Controller
{

    protected $em;
    protected $repository;
    private $userLogged;
    private $role;
    private $session;



    // Set up all necessary variable
    protected function initialise()
    {
        $this->session = new Session();
        $this->em = $this->getDoctrine()->getManager();
        $this->repository = $this->em->getRepository('SolucelAdminBundle:User');
        $this->userLogged = $this->session->get('userLogged');
        $this->role = $this->session->get('userLogged')->getRole()->getName();

    }

			
    /**
     * Lists all User entities.
     *
     */
    public function indexAction()
    {
    	
		$this->get("services")->setVars('user');
        $this->initialise();


        return $this->render('SolucelAdminBundle:User:index.html.twig', array(
            'role' => $this->role
        ));
    }


    public function listDatatablesAction(Request $request)
    {

        $this->get("services")->setVars('user');

        // Set up required variables
        $this->initialise();

        // Get the parameters from DataTable Ajax Call
        if ($request->getMethod() == 'POST')
        {
            $draw = intval($request->request->get('draw'));
            $start = $request->request->get('start');
            $length = $request->request->get('length');
            $search = $request->request->get('search');
            $orders = $request->request->get('order');
            $columns = $request->request->get('columns');

        }
        else // If the request is not a POST one, die hard
            die;

        // Process Parameters
        $myArray = array();
        $arrTMP = array();
        $user = $this->session->get("user_logged");

        //$serviceCenter = $user->getServiceCenter() == null ? 0 : $user->getServiceCenter()->getId();

        //var_dump($myArray);die;

        $results = $this->repository->getRequiredDTData($start, $length, $orders, $search, $columns);
        $objects = $results["results"];
        $selected_objects_count = count($objects);
        //var_dump($selected_objects_count);die;

        $i = 0;
        $response = "";

        foreach ($objects as $key => $entity)
        {
            $response .= '["';

            $j = 0;
            $nbColumn = count($columns);
            foreach ($columns as $key => $column)
            {
                // In all cases where something does not exist or went wrong, return -
                $responseTemp = "-";

                switch($column['name'])
                {
                    case 'id':
                    {
                        $responseTemp = $entity->getId();

                        break;
                    }
                    case 'role':
                    {
                        $responseTemp = $entity->getRole()->getName();
                        break;
                    }

                    case 'username':
                    {
                        $responseTemp = $entity->getUsername();
                        break;
                    }

                    case 'name':
                    {
                        $responseTemp = $entity->getName()." ".$entity->getLastName();
                        break;
                    }

                    case 'email':
                    {
                        $responseTemp = $entity->getEmail();
                        break;
                    }


                    case 'enabled':
                    {
                        $responseTemp = $entity->isEnabled() == 1 ?  "Si" : "No";
                        break;
                    }
                    case 'actions':
                    {

                        $urlEdit = $this->generateUrl('solucel_admin_user_edit', array('id' => $entity->getId()));

                        $edit = "<a href='".$urlEdit."'><div class='btn btn-sm btn-primary'><span class='fa fa-search'></span></div></a>&nbsp;&nbsp;";

                        $urlDelete = $this->generateUrl('solucel_admin_user_delete', array('id' => $entity->getId()));
                        $delete = "<a class='btn btn-danger btn-delete'  href='".$urlDelete."'><span class='fa fa-trash-o'></span></a>";

                        $impersonate = "";
                        if($this->role == "ADMINISTRADOR"){
                            $urlImpersonate = $this->generateUrl('solucel_admin_homepage')."?_switch_user=".$entity->getUsername();
                            $impersonate = "<a class='btn btn-sm btn-default' title='Impersonar' href='".$urlImpersonate."'><i class='fa fa-user'></i><span class='item-label'></span></a>&nbsp;&nbsp;";
                        }

                        $responseTemp = $edit.$delete.$impersonate;
                        break;
                    }

                }

                // Add the found data to the json
                $response .= $this->get("services")->escapeJsonString($responseTemp);

                if(++$j !== $nbColumn)
                    $response .='","';
            }

            $response .= '"]';

            // Not on the last item
            if(++$i !== $selected_objects_count)
                $response .= ',';
        }
        $myItems = $response;
        //($request, $repository, $results, $myItems){
        $return = $this->get("services")->serviceDataTable($request, $this->repository, $results, $myItems, $selected_objects_count);

        return $return;


    }



    /**
     * Creates a new User entity.
     *
     */
    public function newAction(Request $request)
    {
    	$this->get("services")->setVars('user');
        $entity = new User();
        $form   = $this->createCreateForm($entity);
		 
		
        return $this->render('SolucelAdminBundle:User:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

 

    /**
     * Finds and displays a User entity.
     *
     */
    public function showAction($entity)
    {
        $deleteForm = $this->createDeleteForm($entity);

        return $this->render('solucel_admin_user/show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     */
    public function editAction(Request $request, $id)
    {


    	$this->get("services")->setVars('user');
        $this->initialise();

        $entity = $this->em->getRepository('SolucelAdminBundle:User')->find($id);

        if(!$entity){
            throw $this->createNotFoundException('Not found.');
        }

        $deleteForm = $this->createDeleteForm($entity);
		$editForm = $this->createEditForm($entity);
        
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            //print "test";die;
            $em = $this->getDoctrine()->getManager();
            //$em->persist($entity);
            $em->flush();

            return $this->redirectToRoute('solucel_admin_user_edit', array('id' => $id));
        }

        //print "test";die;

        return $this->render('SolucelAdminBundle:User:edit.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
	
    /**
     * Deletes a User entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
    	$this->get("services")->setVars('user');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:User')->find($id);		
        $form = $this->createDeleteForm($entity);
        $form->handleRequest($request);

        //if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SolucelAdminBundle:User')->find($entity);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }


			try{
				
	            $em->remove($entity);
	            $em->flush();        		
			
            } catch (\Doctrine\DBAL\DBALException $e) {
            	//var_dump($e->getCode());die;
                if ($e->getCode() == 0)
                {
                	//var_dump($e->getPrevious()->getCode());die;
                    if (intval($e->getPrevious()->getCode()) == 23000)
                    {
                        $this->get('services')->flashWarningForeignKey($request);
                        return $this->redirectToRoute('solucel_admin_user_index');
                    }
                    else
                    {
                        throw $e;
                    }
                }
                else
                {
                    throw $e;
                }
            }	     		
        	
        //}
		
		$this->get('services')->flashSuccess($request);
        return $this->redirectToRoute('solucel_admin_user_index');
    }

    /**
     * Creates a form to delete a User entity.
     *
     * @param User The User entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('solucel_admin_user_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
	
	
	
	
    /**
     * Creates a new User entity.
     *
     */
    public function createAction(Request $request)
    {
		$this->get("services")->setVars('user');
        $entity = new User();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
		/*print "<pre>";
		var_dump($form->getErrorsAsString());die;
		 * */
		 
		 /////
        ///
        //print "<pre>";
        //var_dump($_REQUEST);DIE;


        $plainPassword = $form['password']->getData();
		
		$checkExistence = $this->get('services')->checkExistence(trim($_REQUEST["user"]["username"]), trim($_REQUEST["user"]["email"]), 0);
        if ($form->isValid() && ($checkExistence == "")) {
        	
			$entity->setPlainPassword($plainPassword);
            $em = $this->getDoctrine()->getManager();
			//var_dump($entity);die;


            $roleID = intval($_REQUEST["user"]["role"]);
            $objRole = $this->em->getRepository('SolucelAdminBundle:Role')->find($roleID);
            $role = $objRole->getName(); //GETS THE NAME
            $entity->setRole($objRole);

            if($role == "ADMINISTRADOR"){
                $entity->setRoles(array("ROLE_SUPER_ADMIN"));
            }
            else{
                $entity->setRoles(array("ROLE_USER"));
            }

			
            $em->persist($entity);
            $em->flush();			
						
			///CREATE AND SEND EMAIL
			//($subject, $from, $to, $body, $bodyView )
			/*
			$body = "{$entity->getName()} {$entity->getLastName()}, haz sido agregado a la plataforma Solucel \n usuario: {$entity->getUsername()}  \n password: {$plainPassword} \n"; 
			$this->get('Services')->generalTemplateMail("Usuario Solucel", $entity->getEmail(), $body);		
			 * */

			$this->get('services')->flashSuccess($request);
            //return $this->redirect($this->generateUrl('solucel_admin_user_index'));
            return $this->redirect($this->get('router')->generate('solucel_admin_user_index'));
	                        

        }		 
		elseif ($checkExistence != ""){
			$request->getSession()->getFlashBag()->add('warning',$checkExistence);
						
		}		
		////

		//return $this->redirectToRoute('solucel_admin_user_edit', array('id' => $id));		
		 
		 /////


        return $this->render('SolucelAdminBundle:User:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a User entity.
     *
     * @param User $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm($entity)
    {
    	
		$this->get("services")->setVars('user');
    	$this->initialise();

		/*
		print "<pre>";
		var_dump($session->get("user_service_center"));die;
		 * */
    	
        $form = $this->createForm(UserType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_user_create'),
            'method' => 'POST',
            'brand' => $this->session->get("user_device_brand"),
            'center' => $this->session->get("user_service_center"),
            'operator' => $this->session->get("user_operator"),
            'role' => $this->role,
            
			//user_device_brand
			//user_operator
			//user_service_center
        ));



        return $form;
    }	
	
	
    /**
    * Creates a form to edit a User entity.
    *
    * @param User $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm($entity)
    {
		$this->get("services")->setVars('user');
    	$this->initialise();


		//var_dump($user->getRole()->getName());die;
    	
        $form = $this->createForm(UserType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_user_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'brand' => $this->session->get("user_device_brand"),
            'center' => $this->session->get("user_service_center"),
            'operator' => $this->session->get("user_operator"),
            'role' => $this->role
            //'client' => $this->userLogged,
        ));



        return $form;
    }
	
	
    /**
     * Edits an existing User entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
    	

		//print "<pre>";
		//var_dump($_REQUEST["user"]);die;
		
		$this->get("services")->setVars('user');
    	$session = new Session();
		
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

		$previous_password = $entity->getPassword();
		
        $deleteForm = $this->createDeleteForm($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);


        $plainPassword = trim($editForm['password']->getData());
		
		$checkExistence = $this->get('services')->checkExistence(trim($_REQUEST["user"]["username"]), trim($_REQUEST["user"]["email"]), $id);
		////
        if ($editForm->isValid() && ($checkExistence == "")) {
            if(($plainPassword != $previous_password) && $plainPassword){
                $entity->setPlainPassword($plainPassword);
            }else{
                $entity->setPassword($previous_password);
            }

            //getUserId
            //LOG
            /*
            $user_id = $this->get('security.context')->getToken()->getUser()->getId();
            
            $user = $em->getRepository('WbcAdministratorBundle:User')->find($user_id);
            $text = $user . ' has updated the user <strong>' . $entity . '</strong>';
            $this->get('log')->add($text, $user, 'update');
			 * 
			 */
            $roleID = intval($_REQUEST["user"]["role"]);
            $objRole = $this->em->getRepository('SolucelAdminBundle:Role')->find($roleID);
            $role = $objRole->getName(); //GETS THE NAME
            $entity->setRole($objRole);

            if($role == "ADMINISTRADOR"){
                $entity->setRoles(array("ROLE_SUPER_ADMIN"));
            }
            else{
                $entity->setRoles(array("ROLE_USER"));
            }


            $em->flush();

			$this->get('services')->flashSuccess($request);
            return $this->redirect($this->generateUrl('solucel_admin_user_index', array('id' => $id)));
			 			 
        }
		elseif ($checkExistence != ""){
			$request->getSession()->getFlashBag()->add('warning',$checkExistence);
						
		}		
		////

		return $this->redirectToRoute('solucel_admin_user_edit', array('id' => $id));		
		/*
        return $this->render('SolucelAdminBundle:User:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
		 * 
		 */
    }	

	public function checkExistence($username, $email, $id){
		
		
		$this->get('services')->checkExistence($username, $email, $id);
		die;
	}



}

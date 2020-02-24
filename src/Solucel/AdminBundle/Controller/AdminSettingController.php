<?php

namespace Solucel\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Session;


use Solucel\AdminBundle\Entity\AdminSetting;
use Solucel\AdminBundle\Form\AdminSettingType;

/**
 * AdminSetting controller.
 *
 */
class AdminSettingController extends Controller
{
    /**
     * Lists all AdminSetting entities.
     *
     */
    public function indexAction()
    {
    	$this->get("services")->setVars('adminSetting');
		$session = new Session();
		
        $em = $this->getDoctrine()->getManager();
		
		
		$service = $session->get("user_logged")->getServiceCenter();
		$serviceID = $service != NULL ? $service->getId() : 0; 


        $entities = $em->getRepository('SolucelAdminBundle:AdminSetting')->findAll();

        return $this->render('SolucelAdminBundle:AdminSetting:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new AdminSetting entity.
     *
     */
    public function newAction(Request $request)
    {
    	$this->get("services")->setVars('adminSetting');
        $entity = new AdminSetting();
        $form   = $this->createCreateForm($entity);
		 
	
        return $this->render('SolucelAdminBundle:AdminSetting:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    

    /**
     * Displays a form to edit an existing AdminSetting entity.
     *
     */
    public function editAction(Request $request, $id)
    {
    	
		$this->get("services")->setVars('adminSetting');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:AdminSetting')->find($id);
				
        $deleteForm = $this->createDeleteForm($entity);
		$editForm = $this->createEditForm($entity);
        
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirectToRoute('solucel_admin_adminsetting_edit', array('id' => $id));
        }

        return $this->render('SolucelAdminBundle:AdminSetting:edit.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            
        ));
    }
	
    /**
     * Deletes a AdminSetting entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
    	
		
    	$this->get("services")->setVars('adminSetting');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:AdminSetting')->find($id);		
        $form = $this->createDeleteForm($entity);
        $form->handleRequest($request);

		//var_dump($entity->getId());die;
        //if ($form->isSubmitted() && $form->isValid()) {


        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AdminSetting entity.');
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
                    return $this->redirectToRoute('solucel_admin_adminsetting_index');
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
        return $this->redirectToRoute('solucel_admin_adminsetting_index');
    }

    /**
     * Creates a form to delete a AdminSetting entity.
     *
     * @param AdminSetting The AdminSetting entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($entity)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('solucel_admin_adminsetting_delete', array('id' => $entity->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
	
	
	
	
    /**
     * Creates a new AdminSetting entity.
     *
     */
    public function createAction(Request $request)
    {
		$this->get("services")->setVars('adminSetting');
        $entity = new AdminSetting();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
		/*print "<pre>";
		var_dump($form->getErrorsAsString());die;
		 * */
		 
        if ($form->isValid()) {
        	$em = $this->getDoctrine()->getManager();

			
			$entity->setUpdatedAtValue();
			
			
            $em->persist($entity);
            $em->flush();


			$this->get('services')->flashSuccess($request);
            return $this->redirect($this->generateUrl('solucel_admin_adminsetting_index'));
			 
        }
		/*
		else{
			print "FORMULARIO NO VALIDO";DIE;
		}
		 * */

        return $this->render('SolucelAdminBundle:AdminSetting:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a AdminSetting entity.
     *
     * @param AdminSetting $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm($entity)
    {
    	
		$this->get("services")->setVars('adminSetting');
    	$session = new Session();
				
    	//$this->setVars();
        $form = $this->createForm(AdminSettingType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_adminsetting_create'),
            'method' => 'POST',

            //'client' => $this->userLogged,
        ));



        return $form;
    }	
	

	
	
    /**
    * Creates a form to edit a AdminSetting entity.
    *
    * @param AdminSetting $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm($entity)
    {
    	
		$this->get("services")->setVars('adminSetting');
    	$session = new Session();
				
		
        $form = $this->createForm(AdminSettingType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_adminsetting_update', array('id' => $entity->getId())),
            'method' => 'PUT',

            //'client' => $this->userLogged,
        ));



        return $form;
    }
	
	
    /**
     * Edits an existing AdminSetting entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
    	$this->get("services")->setVars('adminSetting');
        $session = new Session();

        $em = $this->getDoctrine()->getManager();
        $user = $session->get('user_logged');



        $entity = $em->getRepository('SolucelAdminBundle:AdminSetting')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AdminSetting entity.');
        }

        $deleteForm = $this->createDeleteForm($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

            $entity->setUpdatedAtValue();
            $entity->setUpdatedBy($user);

            $em->flush();

			$this->get('services')->flashSuccess($request);
            return $this->redirect($this->generateUrl('solucel_admin_adminsetting_index', array('id' => $id)));
			 
        }

        return $this->render('SolucelAdminBundle:AdminSetting:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }	



}

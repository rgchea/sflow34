<?php

namespace Solucel\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Session;


use Solucel\AdminBundle\Entity\DeviceModel;
use Solucel\AdminBundle\Form\DeviceModelType;

/**
 * DeviceModel controller.
 *
 */
class DeviceModelController extends Controller
{
    /**
     * Lists all DeviceModel entities.
     *
     */
    public function indexAction()
    {
    	$session = new Session();
    	$this->get("services")->setVars('deviceModel');
		
        $em = $this->getDoctrine()->getManager();

		$user = $session->get("user_logged");
		$deviceBrand =  $user->getDeviceBrand();		
		
		if($deviceBrand != NULL){
			
			$entities = $em->getRepository('SolucelAdminBundle:DeviceModel')->findBy(array("deviceBrand" => $deviceBrand->getId(), 'enabled' => 1), array("name", "ASC"));
			
		}
		else{
			$entities = $em->getRepository('SolucelAdminBundle:DeviceModel')->findBy(array('enabled' => 1), array("name" => "ASC"));
		}		


        return $this->render('SolucelAdminBundle:DeviceModel:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new DeviceModel entity.
     *
     */
    public function newAction(Request $request)
    {
    	$this->get("services")->setVars('deviceModel');
        $entity = new DeviceModel();
        $form   = $this->createCreateForm($entity);
		 
	
        return $this->render('SolucelAdminBundle:DeviceModel:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

 

    /**
     * Finds and displays a DeviceModel entity.
     *
     */
    public function showAction($entity)
    {
        $deleteForm = $this->createDeleteForm($entity);

        return $this->render('solucel_admin_devicemodel/show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing DeviceModel entity.
     *
     */
    public function editAction(Request $request, $id)
    {
    	
		$this->get("services")->setVars('deviceModel');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:DeviceModel')->find($id);
				
        $deleteForm = $this->createDeleteForm($entity);
		$editForm = $this->createEditForm($entity);
        
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirectToRoute('solucel_admin_devicemodel_edit', array('id' => $id));
        }

        return $this->render('SolucelAdminBundle:DeviceModel:edit.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
	
    /**
     * Deletes a DeviceModel entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {

        $this->get("services")->setVars('deviceModel');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:DeviceModel')->find($id);
        $form = $this->createDeleteForm($entity);
        $form->handleRequest($request);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Model entity.');
        }
        else{

            //SOFT DELETE
            $entity->setEnabled(0);
            //$customHelper->blameOnMe($entity);
            $this->em->persist($entity);
            $this->em->flush();

        }

        $this->get('services')->flashSuccess($request);
        return $this->redirectToRoute('solucel_admin_devicemodel_index');

    }



    /**
     * Creates a form to delete a DeviceModel entity.
     *
     * @param DeviceModel The DeviceModel entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('solucel_admin_devicemodel_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
	
	
	
	
    /**
     * Creates a new DeviceModel entity.
     *
     */
    public function createAction(Request $request)
    {
		
		$this->get("services")->setVars('deviceModel');
        $entity = new DeviceModel();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
		/*print "<pre>";
		var_dump($form->getErrorsAsString());die;
		 * */
		 
        if ($form->isValid()) {
        	$em = $this->getDoctrine()->getManager();
			
            $em->persist($entity);
            $em->flush();


			$this->get('services')->flashSuccess($request);
            return $this->redirect($this->generateUrl('solucel_admin_devicemodel_index'));
			 
        }
		/*
		else{
			print "FORMULARIO NO VALIDO";DIE;
		}*/

        return $this->render('SolucelAdminBundle:DeviceModel:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a DeviceModel entity.
     *
     * @param DeviceModel $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm($entity)
    {
		$this->get("services")->setVars('deviceModel');
    	$session = new Session();    	

        $form = $this->createForm(DeviceModelType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_devicemodel_create'),
            'method' => 'POST',
            'brand' => $session->get("user_device_brand"),
            //'client' => $this->userLogged,
        ));


        return $form;
    }	
	

	
	
    /**
    * Creates a form to edit a DeviceModel entity.
    *
    * @param DeviceModel $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm($entity)
    {
		$this->get("services")->setVars('deviceModel');
    	$session = new Session();    	

        $form = $this->createForm(DeviceModelType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_devicemodel_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'brand' => $session->get("user_device_brand"),
            //'client' => $this->userLogged,
        ));

        return $form;
    }
	
	
    /**
     * Edits an existing DeviceModel entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
    	$this->get("services")->setVars('deviceModel');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:DeviceModel')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DeviceModel entity.');
        }

        $deleteForm = $this->createDeleteForm($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

			$this->get('services')->flashSuccess($request);
            return $this->redirect($this->generateUrl('solucel_admin_devicemodel_index', array('id' => $id)));
			 
        }

        return $this->render('SolucelAdminBundle:DeviceModel:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }	



}

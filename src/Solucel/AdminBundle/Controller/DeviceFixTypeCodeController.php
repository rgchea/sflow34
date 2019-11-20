<?php

namespace Solucel\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Session;


use Solucel\AdminBundle\Entity\DeviceFixTypeCode;
use Solucel\AdminBundle\Form\DeviceFixTypeCodeType;

/**
 * DeviceFixTypeCode controller.
 *
 */
class DeviceFixTypeCodeController extends Controller
{
    /**
     * Lists all DeviceFixTypeCode entities.
     *
     */
    public function indexAction()
    {
    	$session = new Session();
    	$this->get("services")->setVars('deviceFixTypeCode');
		
        $em = $this->getDoctrine()->getManager();
		
		
		$user = $session->get("user_logged");
		$deviceBrand =  $user->getDeviceBrand();		
		
		if($deviceBrand != NULL){
			
			$entities = $em->getRepository('SolucelAdminBundle:DeviceFixTypeCode')->findBy(array("deviceBrand" => $deviceBrand->getId()));
			
		}
		else{
			$entities = $em->getRepository('SolucelAdminBundle:DeviceFixTypeCode')->findAll();
		}		

        		

        

        return $this->render('SolucelAdminBundle:DeviceFixTypeCode:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new DeviceFixTypeCode entity.
     *
     */
    public function newAction(Request $request)
    {
    	$this->get("services")->setVars('deviceFixTypeCode');
        $entity = new DeviceFixTypeCode();
        $form   = $this->createCreateForm($entity);
		 
	
        return $this->render('SolucelAdminBundle:DeviceFixTypeCode:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

 

    /**
     * Finds and displays a DeviceFixTypeCode entity.
     *
     */
    public function showAction($entity)
    {
        $deleteForm = $this->createDeleteForm($entity);

        return $this->render('solucel_admin_devicefixtypecode/show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing DeviceFixTypeCode entity.
     *
     */
    public function editAction(Request $request, $id)
    {
    	
		$this->get("services")->setVars('deviceFixTypeCode');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:DeviceFixTypeCode')->find($id);
				
        $deleteForm = $this->createDeleteForm($entity);
		$editForm = $this->createEditForm($entity);
        
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirectToRoute('solucel_admin_devicefixtypecode_edit', array('id' => $id));
        }

        return $this->render('SolucelAdminBundle:DeviceFixTypeCode:edit.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
	
    /**
     * Deletes a DeviceFixTypeCode entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
    	
		$this->get("services")->setVars('deviceFixTypeCode');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:DeviceFixTypeCode')->find($id);		
        $form = $this->createDeleteForm($entity);
        $form->handleRequest($request);

        //if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SolucelAdminBundle:DeviceFixTypeCode')->find($entity);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find DeviceFixTypeCode entity.');
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
                        return $this->redirectToRoute('solucel_admin_devicefixtypecode_index');
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
        return $this->redirectToRoute('solucel_admin_devicefixtypecode_index');
    }

    /**
     * Creates a form to delete a DeviceFixTypeCode entity.
     *
     * @param DeviceFixTypeCode The DeviceFixTypeCode entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('solucel_admin_devicefixtypecode_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
	
	
	
	
    /**
     * Creates a new DeviceFixTypeCode entity.
     *
     */
    public function createAction(Request $request)
    {
		
		$this->get("services")->setVars('deviceFixTypeCode');
        $entity = new DeviceFixTypeCode();
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
            return $this->redirect($this->generateUrl('solucel_admin_devicefixtypecode_index'));
			 
        }
		/*
		else{
			print "FORMULARIO NO VALIDO";DIE;
		}*/

        return $this->render('SolucelAdminBundle:DeviceFixTypeCode:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a DeviceFixTypeCode entity.
     *
     * @param DeviceFixTypeCode $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm($entity)
    {
		$this->get("services")->setVars('deviceFixTypeCode');
    	$session = new Session();    	
    	//$this->setVars();
        $form = $this->createForm(DeviceFixTypeCodeType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_devicefixtypecode_create'),
            'method' => 'POST',
            'brand' => $session->get("user_device_brand"),
            
            //'client' => $this->userLogged,
        ));


        return $form;
    }	
	

	
	
    /**
    * Creates a form to edit a DeviceFixTypeCode entity.
    *
    * @param DeviceFixTypeCode $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm($entity)
    {
    	
		$this->get("services")->setVars('deviceFixTypeCode');
    	$session = new Session();		
    	//$this->setVars();
        $form = $this->createForm(DeviceFixTypeCodeType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_devicefixtypecode_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'brand' => $session->get("user_device_brand"),
            //'client' => $this->userLogged,
        ));


        return $form;
    }
	
	
    /**
     * Edits an existing DeviceFixTypeCode entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
    	$this->get("services")->setVars('deviceFixTypeCode');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:DeviceFixTypeCode')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DeviceFixTypeCode entity.');
        }

        $deleteForm = $this->createDeleteForm($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

			$this->get('services')->flashSuccess($request);
            return $this->redirect($this->generateUrl('solucel_admin_devicefixtypecode_index', array('id' => $id)));
			 
        }

        return $this->render('SolucelAdminBundle:DeviceFixTypeCode:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }	



}

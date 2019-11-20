<?php

namespace Solucel\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Session;


use Solucel\AdminBundle\Entity\DeviceAccessory;
use Solucel\AdminBundle\Form\DeviceAccessoryType;

/**
 * DeviceAccessory controller.
 *
 */
class DeviceAccessoryController extends Controller
{
    /**
     * Lists all DeviceAccessory entities.
     *
     */
    public function indexAction()
    {
    	$this->get("services")->setVars('deviceAccessory');
		
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SolucelAdminBundle:DeviceAccessory')->findAll();

        return $this->render('SolucelAdminBundle:DeviceAccessory:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new DeviceAccessory entity.
     *
     */
    public function newAction(Request $request)
    {
    	$this->get("services")->setVars('deviceAccessory');
        $entity = new DeviceAccessory();
        $form   = $this->createCreateForm($entity);
		 
	
        return $this->render('SolucelAdminBundle:DeviceAccessory:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

 

    /**
     * Finds and displays a DeviceAccessory entity.
     *
     */
    public function showAction($entity)
    {
        $deleteForm = $this->createDeleteForm($entity);

        return $this->render('solucel_admin_deviceaccessory/show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing DeviceAccessory entity.
     *
     */
    public function editAction(Request $request, $id)
    {
    	
		$this->get("services")->setVars('deviceAccessory');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:DeviceAccessory')->find($id);
				
        $deleteForm = $this->createDeleteForm($entity);
		$editForm = $this->createEditForm($entity);
        
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirectToRoute('solucel_admin_deviceaccessory_edit', array('id' => $id));
        }

        return $this->render('SolucelAdminBundle:DeviceAccessory:edit.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
	
    /**
     * Deletes a DeviceAccessory entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
    		
    	$this->get("services")->setVars('deviceAccessory');
    	
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:DeviceAccessory')->find($id);		
        $form = $this->createDeleteForm($entity);
        $form->handleRequest($request);

        //if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SolucelAdminBundle:DeviceAccessory')->find($entity);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find DeviceAccessory entity.');
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
                        return $this->redirectToRoute('solucel_admin_deviceaccessory_index');
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
        return $this->redirectToRoute('solucel_admin_deviceaccessory_index');
    }

    /**
     * Creates a form to delete a DeviceAccessory entity.
     *
     * @param DeviceAccessory The DeviceAccessory entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('solucel_admin_deviceaccessory_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
	
	
	
	
    /**
     * Creates a new DeviceAccessory entity.
     *
     */
    public function createAction(Request $request)
    {
		$this->get("services")->setVars('deviceAccessory');
        $entity = new DeviceAccessory();
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
            return $this->redirect($this->generateUrl('solucel_admin_deviceaccessory_index'));
			 
        }
		/*
		else{
			print "FORMULARIO NO VALIDO";DIE;
		}
		 * */

        return $this->render('SolucelAdminBundle:DeviceAccessory:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a DeviceAccessory entity.
     *
     * @param DeviceAccessory $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm($entity)
    {
    	//$this->setVars();
        $form = $this->createForm(DeviceAccessoryType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_deviceaccessory_create'),
            'method' => 'POST'
            //'client' => $this->userLogged,
        ));



        return $form;
    }	
	

	
	
    /**
    * Creates a form to edit a DeviceAccessory entity.
    *
    * @param DeviceAccessory $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm($entity)
    {
    	//$this->setVars();
        $form = $this->createForm(DeviceAccessoryType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_deviceaccessory_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            //'client' => $this->userLogged,
        ));



        return $form;
    }
	
	
    /**
     * Edits an existing DeviceAccessory entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
    	
    	$this->get("services")->setVars('deviceAccessory');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:DeviceAccessory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DeviceAccessory entity.');
        }

        $deleteForm = $this->createDeleteForm($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

			$this->get('services')->flashSuccess($request);
            return $this->redirect($this->generateUrl('solucel_admin_deviceaccessory_index', array('id' => $id)));
			 
        }

        return $this->render('SolucelAdminBundle:DeviceAccessory:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }	



}

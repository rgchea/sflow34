<?php

namespace Solucel\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Session;


use Solucel\AdminBundle\Entity\DeviceType;
use Solucel\AdminBundle\Form\DeviceTypeType;

/**
 * DeviceType controller.
 *
 */
class DeviceTypeController extends Controller
{
    /**
     * Lists all DeviceType entities.
     *
     */
    public function indexAction()
    {
    	$session = new Session();
    	$this->get("services")->setVars('deviceType');
		
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SolucelAdminBundle:DeviceType')->findAll();

        return $this->render('SolucelAdminBundle:DeviceType:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new DeviceType entity.
     *
     */
    public function newAction(Request $request)
    {
    	$this->get("services")->setVars('deviceType');
        $entity = new DeviceType();
        $form   = $this->createCreateForm($entity);
		 
	
        return $this->render('SolucelAdminBundle:DeviceType:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

 

    /**
     * Finds and displays a DeviceType entity.
     *
     */
    public function showAction($entity)
    {
        $deleteForm = $this->createDeleteForm($entity);

        return $this->render('solucel_admin_devicetype/show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing DeviceType entity.
     *
     */
    public function editAction(Request $request, $id)
    {
    	
		$this->get("services")->setVars('deviceType');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:DeviceType')->find($id);
				
        $deleteForm = $this->createDeleteForm($entity);
		$editForm = $this->createEditForm($entity);
        
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirectToRoute('solucel_admin_devicetype_edit', array('id' => $id));
        }

        return $this->render('SolucelAdminBundle:DeviceType:edit.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
	
    /**
     * Deletes a DeviceType entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
    	$this->get("services")->setVars('deviceType');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:DeviceType')->find($id);		
        $form = $this->createDeleteForm($entity);
        $form->handleRequest($request);

        //if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SolucelAdminBundle:DeviceType')->find($entity);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find DeviceType entity.');
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
                        return $this->redirectToRoute('solucel_admin_devicetype_index');
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
        return $this->redirectToRoute('solucel_admin_devicetype_index');
    }

    /**
     * Creates a form to delete a DeviceType entity.
     *
     * @param DeviceType The DeviceType entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('solucel_admin_devicetype_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
	
	
	
	
    /**
     * Creates a new DeviceType entity.
     *
     */
    public function createAction(Request $request)
    {
		$this->get("services")->setVars('deviceType');
        $entity = new DeviceType();
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
            return $this->redirect($this->generateUrl('solucel_admin_devicetype_index'));
			 
        }
		/*
		else{
			print "FORMULARIO NO VALIDO";DIE;
		}*/

        return $this->render('SolucelAdminBundle:DeviceType:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a DeviceType entity.
     *
     * @param DeviceType $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm($entity)
    {
    	//$this->setVars();
        $form = $this->createForm(DeviceTypeType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_devicetype_create'),
            'method' => 'POST'
            //'client' => $this->userLogged,
        ));


        return $form;
    }	
	

	
	
    /**
    * Creates a form to edit a DeviceType entity.
    *
    * @param DeviceType $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm($entity)
    {
    	//$this->setVars();
        $form = $this->createForm(DeviceTypeType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_devicetype_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            //'client' => $this->userLogged,
        ));

        return $form;
    }
	
	
    /**
     * Edits an existing DeviceType entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
    	$this->get("services")->setVars('deviceType');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:DeviceType')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DeviceType entity.');
        }

        $deleteForm = $this->createDeleteForm($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

			$this->get('services')->flashSuccess($request);
            return $this->redirect($this->generateUrl('solucel_admin_devicetype_index', array('id' => $id)));
			 
        }

        return $this->render('SolucelAdminBundle:DeviceType:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }	



}

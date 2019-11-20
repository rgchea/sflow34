<?php

namespace Solucel\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Session;


use Solucel\AdminBundle\Entity\DeviceFixLevel;
use Solucel\AdminBundle\Form\DeviceFixLevelType;

/**
 * DeviceFixLevel controller.
 *
 */
class DeviceFixLevelController extends Controller
{
    /**
     * Lists all DeviceFixLevel entities.
     *
     */
    public function indexAction()
    {
    	$session = new Session();
    	$this->get("services")->setVars('deviceFixLevel');
		
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SolucelAdminBundle:DeviceFixLevel')->findAll();

        return $this->render('SolucelAdminBundle:DeviceFixLevel:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new DeviceFixLevel entity.
     *
     */
    public function newAction(Request $request)
    {
    	$this->get("services")->setVars('deviceFixLevel');
        $entity = new DeviceFixLevel();
        $form   = $this->createCreateForm($entity);
		 
	
        return $this->render('SolucelAdminBundle:DeviceFixLevel:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

 

    /**
     * Finds and displays a DeviceFixLevel entity.
     *
     */
    public function showAction($entity)
    {
        $deleteForm = $this->createDeleteForm($entity);

        return $this->render('solucel_admin_devicefixlevel/show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing DeviceFixLevel entity.
     *
     */
    public function editAction(Request $request, $id)
    {
    	$this->get("services")->setVars('deviceFixLevel');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:DeviceFixLevel')->find($id);
				
        $deleteForm = $this->createDeleteForm($entity);
		$editForm = $this->createEditForm($entity);
        
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirectToRoute('solucel_admin_devicefixlevel_edit', array('id' => $id));
        }

        return $this->render('SolucelAdminBundle:DeviceFixLevel:edit.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
	
    /**
     * Deletes a DeviceFixLevel entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
    	$this->get("services")->setVars('deviceFixLevel');
    	
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:DeviceFixLevel')->find($id);		
        $form = $this->createDeleteForm($entity);
        $form->handleRequest($request);

        //if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SolucelAdminBundle:DeviceFixLevel')->find($entity);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find DeviceFixLevel entity.');
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
                        return $this->redirectToRoute('solucel_admin_devicefixlevel_index');
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
        return $this->redirectToRoute('solucel_admin_devicefixlevel_index');
    }

    /**
     * Creates a form to delete a DeviceFixLevel entity.
     *
     * @param DeviceFixLevel The DeviceFixLevel entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('solucel_admin_devicefixlevel_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
	
	
	
	
    /**
     * Creates a new DeviceFixLevel entity.
     *
     */
    public function createAction(Request $request)
    {
		
		$this->get("services")->setVars('deviceFixLevel');
        $entity = new DeviceFixLevel();
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
            return $this->redirect($this->generateUrl('solucel_admin_devicefixlevel_index'));
			 
        }
		/*
		else{
			print "FORMULARIO NO VALIDO";DIE;
		}*/

        return $this->render('SolucelAdminBundle:DeviceFixLevel:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a DeviceFixLevel entity.
     *
     * @param DeviceFixLevel $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm($entity)
    {
    	//$this->setVars();
        $form = $this->createForm( DeviceFixLevelType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_devicefixlevel_create'),
            'method' => 'POST'
            //'client' => $this->userLogged,
        ));


        return $form;
    }	
	

	
	
    /**
    * Creates a form to edit a DeviceFixLevel entity.
    *
    * @param DeviceFixLevel $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm($entity)
    {
    	//$this->setVars();
        $form = $this->createForm(DeviceFixLevelType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_devicefixlevel_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            //'client' => $this->userLogged,
        ));


        return $form;
    }
	
	
    /**
     * Edits an existing DeviceFixLevel entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
    	$this->get("services")->setVars('deviceFixLevel');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:DeviceFixLevel')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DeviceFixLevel entity.');
        }

        $deleteForm = $this->createDeleteForm($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

			$this->get('services')->flashSuccess($request);
            return $this->redirect($this->generateUrl('solucel_admin_devicefixlevel_index', array('id' => $id)));
			 
        }

        return $this->render('SolucelAdminBundle:DeviceFixLevel:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }	



}

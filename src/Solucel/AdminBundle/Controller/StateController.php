<?php

namespace Solucel\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Session;


use Solucel\AdminBundle\Entity\State;
use Solucel\AdminBundle\Form\StateType;

/**
 * State controller.
 *
 */
class StateController extends Controller
{
    /**
     * Lists all State entities.
     *
     */
    public function indexAction()
    {
    	$this->get("services")->setVars('state');
		$session = new Session();
		
        $em = $this->getDoctrine()->getManager();
		
		
        $entities = $em->getRepository('SolucelAdminBundle:State')->findAll();

        return $this->render('SolucelAdminBundle:State:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new State entity.
     *
     */
    public function newAction(Request $request)
    {
    	
		
    	$this->get("services")->setVars('state');
        $entity = new State();
        $form   = $this->createCreateForm($entity);
		 
	
        return $this->render('SolucelAdminBundle:State:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

 

    /**
     * Finds and displays a State entity.
     *
     */
    public function showAction($entity)
    {
        $deleteForm = $this->createDeleteForm($entity);

        return $this->render('solucel_admin_agency/show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing State entity.
     *
     */
    public function editAction(Request $request, $id)
    {
    	
		$this->get("services")->setVars('state');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:State')->find($id);
				
        $deleteForm = $this->createDeleteForm($entity);
		$editForm = $this->createEditForm($entity);
        
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirectToRoute('solucel_admin_state_edit', array('id' => $id));
        }

        return $this->render('SolucelAdminBundle:State:edit.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            
        ));
    }
	
    /**
     * Deletes a State entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
    	
		
    	$this->get("services")->setVars('state');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:State')->find($id);		
        $form = $this->createDeleteForm($entity);
        $form->handleRequest($request);

		//var_dump($entity->getId());die;
        //if ($form->isSubmitted() && $form->isValid()) {


        if (!$entity) {
            throw $this->createNotFoundException('Unable to find State entity.');
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
                    return $this->redirectToRoute('solucel_admin_state_index');
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
        return $this->redirectToRoute('solucel_admin_state_index');
    }

    /**
     * Creates a form to delete a State entity.
     *
     * @param State The State entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($entity)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('solucel_admin_state_delete', array('id' => $entity->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
	
	
	
	
    /**
     * Creates a new State entity.
     *
     */
    public function createAction(Request $request)
    {
		$this->get("services")->setVars('state');
        $entity = new State();
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
            return $this->redirect($this->generateUrl('solucel_admin_state_index'));
			 
        }
		/*
		else{
			print "FORMULARIO NO VALIDO";DIE;
		}
		 * */

        return $this->render('SolucelAdminBundle:State:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a State entity.
     *
     * @param State $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm($entity)
    {
    	
		$this->get("services")->setVars('state');
    	$session = new Session();
				
    	//$this->setVars();
        $form = $this->createForm(StateType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_state_create'),
            'method' => 'POST',
            //'operator' => $session->get("user_operator"),            
            //'client' => $this->userLogged,
        ));


        return $form;
    }	
	

	
	
    /**
    * Creates a form to edit a State entity.
    *
    * @param State $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm($entity)
    {
    	
		$this->get("services")->setVars('state');
    	$session = new Session();
				
		
        $form = $this->createForm(StateType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_state_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            //'operator' => $session->get("user_operator"),            
            //'client' => $this->userLogged,
        ));


        return $form;
    }
	
	
    /**
     * Edits an existing State entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
    	$this->get("services")->setVars('state');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:State')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find State entity.');
        }

        $deleteForm = $this->createDeleteForm($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

			$this->get('services')->flashSuccess($request);
            return $this->redirect($this->generateUrl('solucel_admin_state_index', array('id' => $id)));
			 
        }

        return $this->render('SolucelAdminBundle:State:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }	



}

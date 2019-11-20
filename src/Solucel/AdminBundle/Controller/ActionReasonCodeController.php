<?php

namespace Solucel\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Session;


use Solucel\AdminBundle\Entity\ActionReasonCode;
use Solucel\AdminBundle\Form\ActionReasonCodeType;

/**
 * ActionReasonCode controller.
 *
 */
class ActionReasonCodeController extends Controller
{
    /**
     * Lists all ActionReasonCode entities.
     *
     */
    public function indexAction()
    {
    	$this->get("services")->setVars('actionReasonCode');
		$session = new Session();
		
        $em = $this->getDoctrine()->getManager();
		
		
        $entities = $em->getRepository('SolucelAdminBundle:ActionReasonCode')->findAll();

        return $this->render('SolucelAdminBundle:ActionReasonCode:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new ActionReasonCode entity.
     *
     */
    public function newAction(Request $request)
    {
    	
		
    	$this->get("services")->setVars('actionReasonCode');
        $entity = new ActionReasonCode();
        $form   = $this->createCreateForm($entity);
		 
	
        return $this->render('SolucelAdminBundle:ActionReasonCode:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

 

    /**
     * Finds and displays a ActionReasonCode entity.
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
     * Displays a form to edit an existing ActionReasonCode entity.
     *
     */
    public function editAction(Request $request, $id)
    {
    	
		$this->get("services")->setVars('actionReasonCode');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:ActionReasonCode')->find($id);
				
        $deleteForm = $this->createDeleteForm($entity);
		$editForm = $this->createEditForm($entity);
        
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirectToRoute('solucel_admin_actionreasoncode_edit', array('id' => $id));
        }

        return $this->render('SolucelAdminBundle:ActionReasonCode:edit.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            
        ));
    }
	
    /**
     * Deletes a ActionReasonCode entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
    	
		
    	$this->get("services")->setVars('actionReasonCode');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:ActionReasonCode')->find($id);		
        $form = $this->createDeleteForm($entity);
        $form->handleRequest($request);

		//var_dump($entity->getId());die;
        //if ($form->isSubmitted() && $form->isValid()) {


        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ActionReasonCode entity.');
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
                    return $this->redirectToRoute('solucel_admin_actionreasoncode_index');
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
        return $this->redirectToRoute('solucel_admin_actionreasoncode_index');
    }

    /**
     * Creates a form to delete a ActionReasonCode entity.
     *
     * @param ActionReasonCode The ActionReasonCode entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($entity)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('solucel_admin_actionreasoncode_delete', array('id' => $entity->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
	
	
	
	
    /**
     * Creates a new ActionReasonCode entity.
     *
     */
    public function createAction(Request $request)
    {
		$this->get("services")->setVars('actionReasonCode');
        $entity = new ActionReasonCode();
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
            return $this->redirect($this->generateUrl('solucel_admin_actionreasoncode_index'));
			 
        }
		/*
		else{
			print "FORMULARIO NO VALIDO";DIE;
		}
		 * */

        return $this->render('SolucelAdminBundle:ActionReasonCode:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a ActionReasonCode entity.
     *
     * @param ActionReasonCode $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm($entity)
    {
    	
		$this->get("services")->setVars('actionReasonCode');
    	$session = new Session();
				
    	//$this->setVars();
        $form = $this->createForm(ActionReasonCodeType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_actionreasoncode_create'),
            'method' => 'POST',
            //'operator' => $session->get("user_operator"),            
            //'client' => $this->userLogged,
        ));



        return $form;
    }	
	

	
	
    /**
    * Creates a form to edit a ActionReasonCode entity.
    *
    * @param ActionReasonCode $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm($entity)
    {
    	
		$this->get("services")->setVars('actionReasonCode');
    	$session = new Session();
				
		
        $form = $this->createForm(ActionReasonCodeType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_actionreasoncode_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            //'operator' => $session->get("user_operator"),            
            //'client' => $this->userLogged,
        ));



        return $form;
    }
	
	
    /**
     * Edits an existing ActionReasonCode entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
    	$this->get("services")->setVars('actionReasonCode');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:ActionReasonCode')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ActionReasonCode entity.');
        }

        $deleteForm = $this->createDeleteForm($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

			$this->get('services')->flashSuccess($request);
            return $this->redirect($this->generateUrl('solucel_admin_actionreasoncode_index', array('id' => $id)));
			 
        }

        return $this->render('SolucelAdminBundle:ActionReasonCode:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }	



}

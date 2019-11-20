<?php

namespace Solucel\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Session;


use Solucel\AdminBundle\Entity\Payer;
use Solucel\AdminBundle\Form\PayerType;

/**
 * Payer controller.
 *
 */
class PayerController extends Controller
{
    /**
     * Lists all Payer entities.
     *
     */
    public function indexAction()
    {
    	$this->get("services")->setVars('payer');
		$session = new Session();
		
        $em = $this->getDoctrine()->getManager();
		
		
        $entities = $em->getRepository('SolucelAdminBundle:Payer')->findAll();

        return $this->render('SolucelAdminBundle:Payer:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new Payer entity.
     *
     */
    public function newAction(Request $request)
    {
    	
		
    	$this->get("services")->setVars('payer');
        $entity = new Payer();
        $form   = $this->createCreateForm($entity);
		 
	
        return $this->render('SolucelAdminBundle:Payer:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

 

    /**
     * Finds and displays a Payer entity.
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
     * Displays a form to edit an existing Payer entity.
     *
     */
    public function editAction(Request $request, $id)
    {
    	
		$this->get("services")->setVars('payer');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:Payer')->find($id);
				
        $deleteForm = $this->createDeleteForm($entity);
		$editForm = $this->createEditForm($entity);
        
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirectToRoute('solucel_admin_payer_edit', array('id' => $id));
        }

        return $this->render('SolucelAdminBundle:Payer:edit.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            
        ));
    }
	
    /**
     * Deletes a Payer entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
    	
		
    	$this->get("services")->setVars('payer');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:Payer')->find($id);		
        $form = $this->createDeleteForm($entity);
        $form->handleRequest($request);

		//var_dump($entity->getId());die;
        //if ($form->isSubmitted() && $form->isValid()) {


        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Payer entity.');
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
                    return $this->redirectToRoute('solucel_admin_payer_index');
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
        return $this->redirectToRoute('solucel_admin_payer_index');
    }

    /**
     * Creates a form to delete a Payer entity.
     *
     * @param Payer The Payer entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($entity)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('solucel_admin_payer_delete', array('id' => $entity->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
	
	
	
	
    /**
     * Creates a new Payer entity.
     *
     */
    public function createAction(Request $request)
    {
		$this->get("services")->setVars('payer');
        $entity = new Payer();
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
            return $this->redirect($this->generateUrl('solucel_admin_payer_index'));
			 
        }
		/*
		else{
			print "FORMULARIO NO VALIDO";DIE;
		}
		 * */

        return $this->render('SolucelAdminBundle:Payer:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Payer entity.
     *
     * @param Payer $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm($entity)
    {
    	
		$this->get("services")->setVars('payer');
    	$session = new Session();
				
    	//$this->setVars();
        $form = $this->createForm(PayerType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_payer_create'),
            'method' => 'POST',
            //'operator' => $session->get("user_operator"),            
            //'client' => $this->userLogged,
        ));



        return $form;
    }	
	

	
	
    /**
    * Creates a form to edit a Payer entity.
    *
    * @param Payer $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm($entity)
    {
    	
		$this->get("services")->setVars('payer');
    	$session = new Session();
				
		
        $form = $this->createForm(PayerType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_payer_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            //'operator' => $session->get("user_operator"),            
            //'client' => $this->userLogged,
        ));


        return $form;
    }
	
	
    /**
     * Edits an existing Payer entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
    	$this->get("services")->setVars('payer');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:Payer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Payer entity.');
        }

        $deleteForm = $this->createDeleteForm($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

			$this->get('services')->flashSuccess($request);
            return $this->redirect($this->generateUrl('solucel_admin_payer_index', array('id' => $id)));
			 
        }

        return $this->render('SolucelAdminBundle:Payer:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }	



}

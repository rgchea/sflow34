<?php

namespace Solucel\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Session;


use Solucel\AdminBundle\Entity\Agency;
use Solucel\AdminBundle\Form\AgencyType;

/**
 * Agency controller.
 *
 */
class AgencyController extends Controller
{
    /**
     * Lists all Agency entities.
     *
     */
    public function indexAction()
    {
    	$this->get("services")->setVars('agency');
		$session = new Session();
		
        $em = $this->getDoctrine()->getManager();
		
		
		$service = $session->get("user_logged")->getServiceCenter();
		$serviceID = $service != NULL ? $service->getId() : 0; 
		
		$userFilters = $em->getRepository('SolucelAdminBundle:User')->getUserFilters($session->get("user_logged")->getId(), $serviceID);
		//$entities = $em->getRepository('SolucelAdminBundle:Agency')->findAll();
		//var_dump($userFilters);die;
		//die;
		
        $entities = $em->getRepository('SolucelAdminBundle:Agency')->getFilteredAgencies($userFilters);

        return $this->render('SolucelAdminBundle:Agency:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new Agency entity.
     *
     */
    public function newAction(Request $request)
    {
    	$this->get("services")->setVars('agency');
        $entity = new Agency();
        $form   = $this->createCreateForm($entity);
		 
	
        return $this->render('SolucelAdminBundle:Agency:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

 

    /**
     * Finds and displays a Agency entity.
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
     * Displays a form to edit an existing Agency entity.
     *
     */
    public function editAction(Request $request, $id)
    {
    	
		$this->get("services")->setVars('agency');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:Agency')->find($id);
				
        $deleteForm = $this->createDeleteForm($entity);
		$editForm = $this->createEditForm($entity);
        
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirectToRoute('solucel_admin_agency_edit', array('id' => $id));
        }

        return $this->render('SolucelAdminBundle:Agency:edit.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            
        ));
    }
	
    /**
     * Deletes a Agency entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
    	
		
    	$this->get("services")->setVars('agency');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:Agency')->find($id);		
        $form = $this->createDeleteForm($entity);
        $form->handleRequest($request);

		//var_dump($entity->getId());die;
        //if ($form->isSubmitted() && $form->isValid()) {


        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Agency entity.');
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
                    return $this->redirectToRoute('solucel_admin_agency_index');
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
        return $this->redirectToRoute('solucel_admin_agency_index');
    }

    /**
     * Creates a form to delete a Agency entity.
     *
     * @param Agency The Agency entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($entity)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('solucel_admin_agency_delete', array('id' => $entity->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
	
	
	
	
    /**
     * Creates a new Agency entity.
     *
     */
    public function createAction(Request $request)
    {
		$this->get("services")->setVars('agency');
        $entity = new Agency();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
		/*print "<pre>";
		var_dump($form->getErrorsAsString());die;
		 * */
		 
        if ($form->isValid()) {
        	$em = $this->getDoctrine()->getManager();

			
			$entity->setCreatedAtValue();
			
			
            $em->persist($entity);
            $em->flush();


			$this->get('services')->flashSuccess($request);
            return $this->redirect($this->generateUrl('solucel_admin_agency_index'));
			 
        }
		/*
		else{
			print "FORMULARIO NO VALIDO";DIE;
		}
		 * */

        return $this->render('SolucelAdminBundle:Agency:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Agency entity.
     *
     * @param Agency $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm($entity)
    {
    	
		$this->get("services")->setVars('agency');
    	$session = new Session();
				
    	//$this->setVars();
        $form = $this->createForm(AgencyType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_agency_create'),
            'method' => 'POST',
            'operator' => $session->get("user_operator"),            
            //'client' => $this->userLogged,
        ));



        return $form;
    }	
	

	
	
    /**
    * Creates a form to edit a Agency entity.
    *
    * @param Agency $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm($entity)
    {
    	
		$this->get("services")->setVars('agency');
    	$session = new Session();
				
		
        $form = $this->createForm(AgencyType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_agency_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'operator' => $session->get("user_operator"),            
            //'client' => $this->userLogged,
        ));



        return $form;
    }
	
	
    /**
     * Edits an existing Agency entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
    	$this->get("services")->setVars('agency');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:Agency')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Agency entity.');
        }

        $deleteForm = $this->createDeleteForm($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

			$this->get('services')->flashSuccess($request);
            return $this->redirect($this->generateUrl('solucel_admin_agency_index', array('id' => $id)));
			 
        }

        return $this->render('SolucelAdminBundle:Agency:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }	



}

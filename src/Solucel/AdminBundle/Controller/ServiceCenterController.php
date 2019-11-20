<?php

namespace Solucel\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Session;


use Solucel\AdminBundle\Entity\ServiceCenter;
use Solucel\AdminBundle\Form\ServiceCenterType;

/**
 * ServiceCenter controller.
 *
 */
class ServiceCenterController extends Controller
{
    /**
     * Lists all ServiceCenter entities.
     *
     */
    public function indexAction()
    {
    	$session = new Session();
    	$this->get("services")->setVars('serviceCenter');
		
        $em = $this->getDoctrine()->getManager();

		$user = $session->get("user_logged");
		$serviceCenter =  $user->getServiceCenter();
		
		//var_dump($serviceCenter->getId());die;
		
		if($serviceCenter != NULL){
			//$agencies = $em->getRepository('SolucelAdminBundle:Agency')->findBy(array("operator" => intval($_REQUEST["operator_id"]), "enabled" => 1) );
			$entities = $em->getRepository('SolucelAdminBundle:ServiceCenter')->findBy(array("id" => $serviceCenter->getId(), "enabled" => 1 ));
			$all_access = 0;
		}
		else{
			$entities = $em->getRepository('SolucelAdminBundle:ServiceCenter')->findAll();
			$all_access = 1;	
		}
        

        return $this->render('SolucelAdminBundle:ServiceCenter:index.html.twig', array(
            'entities' => $entities,
            'all_access' => $all_access,
			
        ));
    }

    /**
     * Creates a new ServiceCenter entity.
     *
     */
    public function newAction(Request $request)
    {
    	$this->get("services")->setVars('serviceCenter');
        $entity = new ServiceCenter();
        $form   = $this->createCreateForm($entity);
		 
	
        return $this->render('SolucelAdminBundle:ServiceCenter:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

 

    /**
     * Finds and displays a ServiceCenter entity.
     *
     */
    public function showAction($entity)
    {
        $deleteForm = $this->createDeleteForm($entity);

        return $this->render('solucel_admin_servicecenter/show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ServiceCenter entity.
     *
     */
    public function editAction(Request $request, $id)
    {
    	$this->get("services")->setVars('serviceCenter');
    	
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:ServiceCenter')->find($id);
				
        $deleteForm = $this->createDeleteForm($entity);
		$editForm = $this->createEditForm($entity);
        
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirectToRoute('solucel_admin_servicecenter_edit', array('id' => $id));
        }

        return $this->render('SolucelAdminBundle:ServiceCenter:edit.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
	
    /**
     * Deletes a ServiceCenter entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
    	$this->get("services")->setVars('serviceCenter');
    	
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:ServiceCenter')->find($id);		
        $form = $this->createDeleteForm($entity);
        $form->handleRequest($request);

        //if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SolucelAdminBundle:ServiceCenter')->find($entity);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ServiceCenter entity.');
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
                        return $this->redirectToRoute('solucel_admin_servicecenter_index');
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
        return $this->redirectToRoute('solucel_admin_servicecenter_index');
    }

    /**
     * Creates a form to delete a ServiceCenter entity.
     *
     * @param ServiceCenter The ServiceCenter entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($entity)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('solucel_admin_servicecenter_delete', array('id' => $entity->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
	
	
	
	
    /**
     * Creates a new ServiceCenter entity.
     *
     */
    public function createAction(Request $request)
    {
		
		$this->get("services")->setVars('serviceCenter');
        $entity = new ServiceCenter();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
		/*print "<pre>";
		var_dump($form->getErrorsAsString());die;
		 * */
		 
        if ($form->isValid()) {
        	$em = $this->getDoctrine()->getManager();
			/*
        	$myRequest = $request->request->get("role");
			//var_dump($myRequest);die;
			$em = $this->getDoctrine()->getManager();
			//var_dump($request->get("role");die;
			
			$entity->setName($myRequest["name"]);
			 * */
			
			$entity->setCreatedAtValue();
			
			
            $em->persist($entity);
            $em->flush();


			$this->get('services')->flashSuccess($request);
            return $this->redirect($this->generateUrl('solucel_admin_servicecenter_index'));
			 
        }


        return $this->render('SolucelAdminBundle:ServiceCenter:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a ServiceCenter entity.
     *
     * @param ServiceCenter $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm($entity)
    {
    	//$this->setVars();
        $form = $this->createForm(ServiceCenterType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_servicecenter_create'),
            'method' => 'POST'
            //'client' => $this->userLogged,
        ));


        return $form;
    }	
	

	
	
    /**
    * Creates a form to edit a ServiceCenter entity.
    *
    * @param ServiceCenter $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm($entity)
    {
    	//$this->setVars();
        $form = $this->createForm(ServiceCenterType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_servicecenter_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            //'client' => $this->userLogged,
        ));



        return $form;
    }
	
	
    /**
     * Edits an existing ServiceCenter entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
    	$this->get("services")->setVars('serviceCenter');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:ServiceCenter')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ServiceCenter entity.');
        }

        $deleteForm = $this->createDeleteForm($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

			$this->get('services')->flashSuccess($request);
            return $this->redirect($this->generateUrl('solucel_admin_servicecenter_index', array('id' => $id)));
			 
        }

        return $this->render('SolucelAdminBundle:ServiceCenter:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }	



}

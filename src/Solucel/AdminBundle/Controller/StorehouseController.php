<?php

namespace Solucel\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Session;


use Solucel\AdminBundle\Entity\Storehouse;
use Solucel\AdminBundle\Form\StorehouseType;

/**
 * Storehouse controller.
 *
 */
class StorehouseController extends Controller
{
    /**
     * Lists all Storehouse entities.
     *
     */
    public function indexAction()
    {

		
				
    	$session = new Session();
    	$this->get("services")->setVars('storehouse');
		
        $em = $this->getDoctrine()->getManager();


		$user = $session->get("user_logged");
		$serviceCenter =  $user->getServiceCenter();
		
		//var_dump($serviceCenter->getId());die;
		
		if($serviceCenter != NULL){
			//$agencies = $em->getRepository('SolucelAdminBundle:Agency')->findBy(array("operator" => intval($_REQUEST["operator_id"]), "enabled" => 1) );
			$entities = $em->getRepository('SolucelAdminBundle:Storehouse')->findBy(array("id" => $serviceCenter->getStorehouse()->getId(), "enabled" => 1 ));
			$all_access = 0;
		}
		else{
			$entities = $em->getRepository('SolucelAdminBundle:Storehouse')->findAll();
			$all_access = 1;	
		}


        //$entities = $em->getRepository('SolucelAdminBundle:Storehouse')->findAll();

        return $this->render('SolucelAdminBundle:Storehouse:index.html.twig', array(
            'entities' => $entities,
            'all_access' => $all_access,
        ));
    }

    /**
     * Creates a new Storehouse entity.
     *
     */
    public function newAction(Request $request)
    {
    	$this->get("services")->setVars('storehouse');
        $entity = new Storehouse();
        $form   = $this->createCreateForm($entity);
		 
	
        return $this->render('SolucelAdminBundle:Storehouse:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

 

    /**
     * Finds and displays a Storehouse entity.
     *
     */
    public function showAction($entity)
    {
        $deleteForm = $this->createDeleteForm($entity);

        return $this->render('solucel_admin_storehouse/show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Storehouse entity.
     *
     */
    public function editAction(Request $request, $id)
    {
    	$this->get("services")->setVars('storehouse');
    	
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:Storehouse')->find($id);
				
        $deleteForm = $this->createDeleteForm($entity);
		$editForm = $this->createEditForm($entity);
        
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirectToRoute('solucel_admin_storehouse_edit', array('id' => $id));
        }

        return $this->render('SolucelAdminBundle:Storehouse:edit.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
	
    /**
     * Deletes a Storehouse entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
    	$this->get("services")->setVars('storehouse');
    	
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:Storehouse')->find($id);		
        $form = $this->createDeleteForm($entity);
        $form->handleRequest($request);

        //if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SolucelAdminBundle:Storehouse')->find($entity);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Storehouse entity.');
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
                        return $this->redirectToRoute('solucel_admin_storehouse_index');
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
        return $this->redirectToRoute('solucel_admin_storehouse_index');
    }

    /**
     * Creates a form to delete a Storehouse entity.
     *
     * @param Storehouse The Storehouse entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($entity)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('solucel_admin_storehouse_delete', array('id' => $entity->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
	
	
	
	
    /**
     * Creates a new Storehouse entity.
     *
     */
    public function createAction(Request $request)
    {
		
		$this->get("services")->setVars('storehouse');
        $entity = new Storehouse();
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
            return $this->redirect($this->generateUrl('solucel_admin_storehouse_index'));
			 
        }


        return $this->render('SolucelAdminBundle:Storehouse:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Storehouse entity.
     *
     * @param Storehouse $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm($entity)
    {
    	//$this->setVars();
        $form = $this->createForm(StorehouseType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_storehouse_create'),
            'method' => 'POST'
            //'client' => $this->userLogged,
        ));


        return $form;
    }	
	

	
	
    /**
    * Creates a form to edit a Storehouse entity.
    *
    * @param Storehouse $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm($entity)
    {
    	//$this->setVars();
        $form = $this->createForm(StorehouseType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_storehouse_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            //'client' => $this->userLogged,
        ));


        return $form;
    }
	
	
    /**
     * Edits an existing Storehouse entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
    	$this->get("services")->setVars('storehouse');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:Storehouse')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Storehouse entity.');
        }

        $deleteForm = $this->createDeleteForm($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

			$this->get('services')->flashSuccess($request);
            return $this->redirect($this->generateUrl('solucel_admin_storehouse_index', array('id' => $id)));
			 
        }

        return $this->render('SolucelAdminBundle:Storehouse:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }	



}

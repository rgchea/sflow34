<?php

namespace Solucel\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Session;


use Solucel\AdminBundle\Entity\Operator;
use Solucel\AdminBundle\Form\OperatorType;

/**
 * Operator controller.
 *
 */
class OperatorController extends Controller
{
    /**
     * Lists all Operator entities.
     *
     */
    public function indexAction()
    {
    	$session = new Session();
    	$this->get("services")->setVars('operator');
		
        $em = $this->getDoctrine()->getManager();
		
		$user = $session->get("user_logged");
		$operator =  $user->getOperator();
		
		//var_dump($serviceCenter->getId());die;
		
		if($operator != NULL){
			//$agencies = $em->getRepository('SolucelAdminBundle:Agency')->findBy(array("operator" => intval($_REQUEST["operator_id"]), "enabled" => 1) );
			$entities = $em->getRepository('SolucelAdminBundle:Operator')->findBy(array("id" => $operator->getId(), "enabled" => 1 ));
			$all_access = 0;
		}
		else{
			$entities = $em->getRepository('SolucelAdminBundle:Operator')->findAll();
			$all_access = 1;	
		}		

        

        return $this->render('SolucelAdminBundle:Operator:index.html.twig', array(
            'entities' => $entities,
            'all_access' => $all_access,
        ));
    }

    /**
     * Creates a new Operator entity.
     *
     */
    public function newAction(Request $request)
    {
    	$this->get("services")->setVars('operator');
        $entity = new Operator();
        $form   = $this->createCreateForm($entity);
		 
	
        return $this->render('SolucelAdminBundle:Operator:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

 

    /**
     * Finds and displays a Operator entity.
     *
     */
    public function showAction($entity)
    {
        $deleteForm = $this->createDeleteForm($entity);

        return $this->render('solucel_admin_operator/show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Operator entity.
     *
     */
    public function editAction(Request $request, $id)
    {
    	
		$this->get("services")->setVars('operator');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:Operator')->find($id);
				
        $deleteForm = $this->createDeleteForm($entity);
		$editForm = $this->createEditForm($entity);
        
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirectToRoute('solucel_admin_operator_edit', array('id' => $id));
        }

        return $this->render('SolucelAdminBundle:Operator:edit.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
	
    /**
     * Deletes a Operator entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
    	
		$this->get("services")->setVars('operator');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:Operator')->find($id);		
        $form = $this->createDeleteForm($entity);
        $form->handleRequest($request);

        //if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SolucelAdminBundle:Operator')->find($entity);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Operator entity.');
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
                        return $this->redirectToRoute('solucel_admin_operator_index');
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
        return $this->redirectToRoute('solucel_admin_operator_index');
    }

    /**
     * Creates a form to delete a Operator entity.
     *
     * @param Operator The Operator entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('solucel_admin_operator_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
	
	
	
	
    /**
     * Creates a new Operator entity.
     *
     */
    public function createAction(Request $request)
    {
		
		$this->get("services")->setVars('operator');
        $entity = new Operator();
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
            return $this->redirect($this->generateUrl('solucel_admin_operator_index'));
			 
        }
		/*
		else{
			print "FORMULARIO NO VALIDO";DIE;
		}*/

        return $this->render('SolucelAdminBundle:Operator:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Operator entity.
     *
     * @param Operator $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm($entity)
    {
    	//$this->setVars();
        $form = $this->createForm(OperatorType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_operator_create'),
            'method' => 'POST'
            //'client' => $this->userLogged,
        ));


        return $form;
    }	
	

	
	
    /**
    * Creates a form to edit a Operator entity.
    *
    * @param Operator $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm($entity)
    {
    	//$this->setVars();
        $form = $this->createForm(OperatorType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_operator_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            //'client' => $this->userLogged,
        ));


        return $form;
    }
	
	
    /**
     * Edits an existing Operator entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
    	$this->get("services")->setVars('operator');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:Operator')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Operator entity.');
        }

        $deleteForm = $this->createDeleteForm($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $entity->preUpload();
			$entity->upload();
			$em->persist($entity);            
            $em->flush();


			$this->get('services')->flashSuccess($request);
            return $this->redirect($this->generateUrl('solucel_admin_operator_index', array('id' => $id)));
			 
        }

        return $this->render('SolucelAdminBundle:Operator:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }	



}

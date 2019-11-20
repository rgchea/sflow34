<?php

namespace Solucel\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Session;


use Solucel\AdminBundle\Entity\CompanyDepartment;
use Solucel\AdminBundle\Form\CompanyDepartmentType;

/**
 * CompanyDepartment controller.
 *
 */
class CompanyDepartmentController extends Controller
{
    /**
     * Lists all CompanyDepartment entities.
     *
     */
    public function indexAction()
    {
    	$session = new Session();
    	$this->get("services")->setVars('companyDepartment');
		
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SolucelAdminBundle:CompanyDepartment')->findAll();

        return $this->render('SolucelAdminBundle:CompanyDepartment:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new CompanyDepartment entity.
     *
     */
    public function newAction(Request $request)
    {
    	$this->get("services")->setVars('companyDepartment');
        $entity = new CompanyDepartment();
        $form   = $this->createCreateForm($entity);
		 
	
        return $this->render('SolucelAdminBundle:CompanyDepartment:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

 

    /**
     * Finds and displays a CompanyDepartment entity.
     *
     */
    public function showAction($entity)
    {
        $deleteForm = $this->createDeleteForm($entity);

        return $this->render('solucel_admin_companydepartment/show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing CompanyDepartment entity.
     *
     */
    public function editAction(Request $request, $id)
    {
    	
		$this->get("services")->setVars('companyDepartment');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:CompanyDepartment')->find($id);
				
        $deleteForm = $this->createDeleteForm($entity);
		$editForm = $this->createEditForm($entity);
        
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirectToRoute('solucel_admin_companydepartment_edit', array('id' => $id));
        }

        return $this->render('SolucelAdminBundle:CompanyDepartment:edit.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
	
    /**
     * Deletes a CompanyDepartment entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
    	
    	$this->get("services")->setVars('companyDepartment');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:CompanyDepartment')->find($id);		
        $form = $this->createDeleteForm($entity);
        $form->handleRequest($request);

        //if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SolucelAdminBundle:CompanyDepartment')->find($entity);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CompanyDepartment entity.');
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
                        return $this->redirectToRoute('solucel_admin_companydepartment_index');
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
        return $this->redirectToRoute('solucel_admin_companydepartment_index');
    }

    /**
     * Creates a form to delete a CompanyDepartment entity.
     *
     * @param CompanyDepartment The CompanyDepartment entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('solucel_admin_companydepartment_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
	
	
	
	
    /**
     * Creates a new CompanyDepartment entity.
     *
     */
    public function createAction(Request $request)
    {
		
		$this->get("services")->setVars('companyDepartment');
        $entity = new CompanyDepartment();
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
			
			//$entity->setCreatedAtValue();
			
			
            $em->persist($entity);
            $em->flush();


			$this->get('services')->flashSuccess($request);
            return $this->redirect($this->generateUrl('solucel_admin_companydepartment_index'));
			 
        }
		/*
		else{
			print "FORMULARIO NO VALIDO";DIE;
		}
		 * */

        return $this->render('SolucelAdminBundle:CompanyDepartment:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a CompanyDepartment entity.
     *
     * @param CompanyDepartment $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm($entity)
    {
    	//$this->setVars();
        $form = $this->createForm(CompanyDepartmentType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_companydepartment_create'),
            'method' => 'POST'
            //'client' => $this->userLogged,
        ));


        return $form;
    }	
	

	
	
    /**
    * Creates a form to edit a CompanyDepartment entity.
    *
    * @param CompanyDepartment $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm($entity)
    {
    	//$this->setVars();
        $form = $this->createForm(CompanyDepartmentType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_companydepartment_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            //'client' => $this->userLogged,
        ));



        return $form;
    }
	
	
    /**
     * Edits an existing CompanyDepartment entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
    		
    	$this->get("services")->setVars('companyDepartment');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:CompanyDepartment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CompanyDepartment entity.');
        }

        $deleteForm = $this->createDeleteForm($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

			$this->get('services')->flashSuccess($request);
            return $this->redirect($this->generateUrl('solucel_admin_companydepartment_index', array('id' => $id)));
			 
        }

        return $this->render('SolucelAdminBundle:CompanyDepartment:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }	



}

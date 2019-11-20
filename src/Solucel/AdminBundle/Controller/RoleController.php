<?php

namespace Solucel\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Session;


use Solucel\AdminBundle\Entity\Role;
use Solucel\AdminBundle\Form\RoleType;

/**
 * Role controller.
 *
 */
class RoleController extends Controller
{
    /**
     * Lists all Role entities.
     *
     */
    public function indexAction()
    {
    	$this->get("services")->setVars('role');
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SolucelAdminBundle:Role')->findAll();

        return $this->render('SolucelAdminBundle:Role:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new Role entity.
     *
     */
    public function newAction(Request $request)
    {
    	$this->get("services")->setVars('role');
    	/*
        $role = new Role();
        $form = $this->createForm('Solucel\AdminBundle\Form\RoleType', $role);
        $form->handleRequest($request);
		 * */
		
		/*
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($role);
            $em->flush();

            return $this->redirectToRoute('solucel_admin_role_show', array('id' => $role->getId()));
        }
		 * */
        $entity = new Role();
        $form   = $this->createCreateForm($entity);
		 
	
        return $this->render('SolucelAdminBundle:Role:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

 

    /**
     * Finds and displays a Role entity.
     *
     */
    public function showAction($entity)
    {
        $deleteForm = $this->createDeleteForm($entity);

        return $this->render('solucel_admin_role/show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Role entity.
     *
     */
    public function editAction(Request $request, $id)
    {
    	$this->get("services")->setVars('role');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:Role')->find($id);
				
        $deleteForm = $this->createDeleteForm($entity);
		$editForm = $this->createEditForm($entity);
        //$editForm = $this->createForm('Solucel\AdminBundle\Form\RoleType', $role);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirectToRoute('solucel_admin_role_edit', array('id' => $id));
        }

        return $this->render('SolucelAdminBundle:Role:edit.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
	
    /**
     * Deletes a Role entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
    	
		$this->get("services")->setVars('role');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:Role')->find($id);		
        $form = $this->createDeleteForm($entity);
        $form->handleRequest($request);

        //if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SolucelAdminBundle:Role')->find($entity);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Role entity.');
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
                        return $this->redirectToRoute('solucel_admin_role_index');
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
        return $this->redirectToRoute('solucel_admin_role_index');
    }

    /**
     * Creates a form to delete a Role entity.
     *
     * @param Role $role The Role entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('solucel_admin_role_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
	
	
	
	
    /**
     * Creates a new Role entity.
     *
     */
    public function createAction(Request $request)
    {
    	
		$this->get("services")->setVars('role');
    	/*
    	print "<pre>";
		var_dump($_REQUEST);
		var_dump($request->request->get('role'));
		
		die;
		/*
		unset($_REQUEST["PHPSESSID"]);
		unset($_REQUEST["REMEMBERME"]);
		 * */
		
        $entity = new Role();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
		/*print "<pre>";
		var_dump($form->getErrorsAsString());die;
		 * */
		 
        if ($form->isValid()) {
        	$myRequest = $request->request->get("role");
			//var_dump($myRequest);die;
			$em = $this->getDoctrine()->getManager();
			//var_dump($request->get("role");die;
			
			$entity->setName( strtoupper($this->get("services")->quitar_tildes(trim($myRequest["name"])))  );
			$entity->setCompanyDepartment($em->getRepository('SolucelAdminBundle:CompanyDepartment')->find(intval($myRequest["companyDepartment"])));
			
            $em->persist($entity);
            $em->flush();


			$this->get('services')->flashSuccess($request);
            return $this->redirect($this->generateUrl('solucel_admin_role_index'));
			 
        }
		/*
		else{
			print "FORMULARIO NO VALIDO";DIE;
		}
		 * */

        return $this->render('SolucelAdminBundle:Role:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Role entity.
     *
     * @param Role $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm($entity)
    {
    	//$this->setVars();
        $form = $this->createForm(RoleType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_role_create'),
            'method' => 'POST'
            //'client' => $this->userLogged,
        ));


        return $form;
    }	
	

	
	
    /**
    * Creates a form to edit a Role entity.
    *
    * @param Role $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm($entity)
    {
    	//$this->setVars();
        $form = $this->createForm(RoleType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_role_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            //'client' => $this->userLogged,
        ));


        return $form;
    }
	
	
    /**
     * Edits an existing Role entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
    	$this->get("services")->setVars('role');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:Role')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Role entity.');
        }

        $deleteForm = $this->createDeleteForm($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
        	$myRequest = $request->request->get("role");
			$entity->setName( strtoupper($this->get("services")->quitar_tildes(trim($myRequest["name"])))  );
            $em->flush();

			$this->get('services')->flashSuccess($request);
            return $this->redirect($this->generateUrl('solucel_admin_role_index', array('id' => $id)));
			 
        }

        return $this->render('SolucelAdminBundle:Role:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }	



}

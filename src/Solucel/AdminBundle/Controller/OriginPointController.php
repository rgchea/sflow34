<?php

namespace Solucel\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Session;


use Solucel\AdminBundle\Entity\OriginPoint;
use Solucel\AdminBundle\Form\OriginPointType;

/**
 * OriginPoint controller.
 *
 */
class OriginPointController extends Controller
{
    /**
     * Lists all OriginPoint entities.
     *
     */
    public function indexAction()
    {
    	$session = new Session();
    	$this->get("services")->setVars('originPoint');
		
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SolucelAdminBundle:OriginPoint')->findAll();

        return $this->render('SolucelAdminBundle:OriginPoint:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new OriginPoint entity.
     *
     */
    public function newAction(Request $request)
    {
    	
		$this->get("services")->setVars('originPoint');
        $entity = new OriginPoint();
        $form   = $this->createCreateForm($entity);
		 
	
        return $this->render('SolucelAdminBundle:OriginPoint:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

 

    /**
     * Finds and displays a OriginPoint entity.
     *
     */
    public function showAction($entity)
    {
        $deleteForm = $this->createDeleteForm($entity);

        return $this->render('solucel_admin_originpoint/show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing OriginPoint entity.
     *
     */
    public function editAction(Request $request, $id)
    {
    	
		$this->get("services")->setVars('originPoint');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:OriginPoint')->find($id);
				
        $deleteForm = $this->createDeleteForm($entity);
		$editForm = $this->createEditForm($entity);
        
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirectToRoute('solucel_admin_originpoint_edit', array('id' => $id));
        }

        return $this->render('SolucelAdminBundle:OriginPoint:edit.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
	
    /**
     * Deletes a OriginPoint entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
    		
    	$this->get("services")->setVars('originPoint');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:OriginPoint')->find($id);		
        $form = $this->createDeleteForm($entity);
        $form->handleRequest($request);

        //if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SolucelAdminBundle:OriginPoint')->find($entity);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find OriginPoint entity.');
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
                        return $this->redirectToRoute('solucel_admin_originpoint_index');
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
        return $this->redirectToRoute('solucel_admin_originpoint_index');
    }

    /**
     * Creates a form to delete a OriginPoint entity.
     *
     * @param OriginPoint The OriginPoint entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($entity)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('solucel_admin_originpoint_delete', array('id' => $entity->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
	
	
	
	
    /**
     * Creates a new OriginPoint entity.
     *
     */
    public function createAction(Request $request)
    {
		
        $entity = new OriginPoint();
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
            return $this->redirect($this->generateUrl('solucel_admin_originpoint_index'));
			 
        }
		/*
		else{
			print "FORMULARIO NO VALIDO";DIE;
		}*/

        return $this->render('SolucelAdminBundle:OriginPoint:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a OriginPoint entity.
     *
     * @param OriginPoint $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm($entity)
    {
    	//$this->setVars();
        $form = $this->createForm(OriginPointType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_originpoint_create'),
            'method' => 'POST'
            //'client' => $this->userLogged,
        ));


        return $form;
    }	
	

	
	
    /**
    * Creates a form to edit a OriginPoint entity.
    *
    * @param OriginPoint $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm($entity)
    {
    	//$this->setVars();
        $form = $this->createForm(OriginPointType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_originpoint_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            //'client' => $this->userLogged,
        ));


        return $form;
    }
	
	
    /**
     * Edits an existing OriginPoint entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
    	$this->get("services")->setVars('originPoint');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:OriginPoint')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OriginPoint entity.');
        }

        $deleteForm = $this->createDeleteForm($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

			$this->get('services')->flashSuccess($request);
            return $this->redirect($this->generateUrl('solucel_admin_originpoint_index', array('id' => $id)));
			 
        }

        return $this->render('SolucelAdminBundle:OriginPoint:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }	



}

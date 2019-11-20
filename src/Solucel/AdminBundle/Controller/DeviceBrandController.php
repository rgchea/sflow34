<?php

namespace Solucel\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Session;


use Solucel\AdminBundle\Entity\DeviceBrand;
use Solucel\AdminBundle\Form\DeviceBrandType;

/**
 * DeviceBrand controller.
 *
 */
class DeviceBrandController extends Controller
{
    /**
     * Lists all DeviceBrand entities.
     *
     */
    public function indexAction()
    {
    	$session = new Session();
    	$this->get("services")->setVars('deviceBrand');
		
        $em = $this->getDoctrine()->getManager();

		$user = $session->get("user_logged");
		$deviceBrand =  $user->getDeviceBrand();
		
		//var_dump($serviceCenter->getId());die;
		
		if($deviceBrand != NULL){
			
			$entities = $em->getRepository('SolucelAdminBundle:DeviceBrand')->findBy(array("id" => $deviceBrand->getId(), "enabled" => 1 ));
			$all_access = 0;
		}
		else{
			$entities = $em->getRepository('SolucelAdminBundle:DeviceBrand')->findAll();
			$all_access = 1;	
		}

        

        return $this->render('SolucelAdminBundle:DeviceBrand:index.html.twig', array(
            'entities' => $entities,
            'all_access' => $all_access,
        ));
    }

    /**
     * Creates a new DeviceBrand entity.
     *
     */
    public function newAction(Request $request)
    {
    	
		$this->get("services")->setVars('deviceBrand');
        $entity = new DeviceBrand();
        $form   = $this->createCreateForm($entity);
		 
	
        return $this->render('SolucelAdminBundle:DeviceBrand:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

 

    /**
     * Finds and displays a DeviceBrand entity.
     *
     */
    public function showAction($entity)
    {
        $deleteForm = $this->createDeleteForm($entity);

        return $this->render('solucel_admin_devicebrand/show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing DeviceBrand entity.
     *
     */
    public function editAction(Request $request, $id)
    {
    	
		$this->get("services")->setVars('deviceBrand');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:DeviceBrand')->find($id);
				
        $deleteForm = $this->createDeleteForm($entity);
		$editForm = $this->createEditForm($entity);
        
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirectToRoute('solucel_admin_devicebrand_edit', array('id' => $id));
        }

        return $this->render('SolucelAdminBundle:DeviceBrand:edit.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
	
    /**
     * Deletes a DeviceBrand entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
    	
		$this->get("services")->setVars('deviceBrand');	
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:DeviceBrand')->find($id);		
        $form = $this->createDeleteForm($entity);
        $form->handleRequest($request);

        //if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SolucelAdminBundle:DeviceBrand')->find($entity);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find DeviceBrand entity.');
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
                        return $this->redirectToRoute('solucel_admin_devicebrand_index');
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
        return $this->redirectToRoute('solucel_admin_devicebrand_index');
    }

    /**
     * Creates a form to delete a DeviceBrand entity.
     *
     * @param DeviceBrand The DeviceBrand entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('solucel_admin_devicebrand_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
	
	
	
	
    /**
     * Creates a new DeviceBrand entity.
     *
     */
    public function createAction(Request $request)
    {
	
		$this->get("services")->setVars('deviceBrand');	
        $entity = new DeviceBrand();
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
            return $this->redirect($this->generateUrl('solucel_admin_devicebrand_index'));
			 
        }
		/*
		else{
			print "FORMULARIO NO VALIDO";DIE;
		}*/

        return $this->render('SolucelAdminBundle:DeviceBrand:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a DeviceBrand entity.
     *
     * @param DeviceBrand $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm($entity)
    {
    	//$this->setVars();
        $form = $this->createForm(DeviceBrandType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_devicebrand_create'),
            'method' => 'POST'
            //'client' => $this->userLogged,
        ));


        return $form;
    }	
	

	
	
    /**
    * Creates a form to edit a DeviceBrand entity.
    *
    * @param DeviceBrand $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm($entity)
    {
    	//$this->setVars();
        $form = $this->createForm(DeviceBrandType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_devicebrand_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            //'client' => $this->userLogged,
        ));

        return $form;
    }
	
	
    /**
     * Edits an existing DeviceBrand entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
    	$this->get("services")->setVars('deviceBrand');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:DeviceBrand')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DeviceBrand entity.');
        }

        $deleteForm = $this->createDeleteForm($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

			$this->get('services')->flashSuccess($request);
            return $this->redirect($this->generateUrl('solucel_admin_devicebrand_index', array('id' => $id)));
			 
        }

        return $this->render('SolucelAdminBundle:DeviceBrand:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }	



}

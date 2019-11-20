<?php

namespace Solucel\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Session;


use Solucel\AdminBundle\Entity\RepairOrderDelivery;
use Solucel\AdminBundle\Form\RepairOrderDeliveryType;

/**
 * RepairOrderDelivery controller.
 *
 */
class RepairOrderDeliveryController extends Controller
{
    /**
     * Lists all RepairOrderDelivery entities.
     *
     */
    public function indexAction()
    {
    	$this->get("services")->setVars('repairOrderDelivery');$session = new Session();
		$session = new Session();
		
        $em = $this->getDoctrine()->getManager();
		
		
	
        $entities = $em->getRepository('SolucelAdminBundle:RepairOrderDelivery')->findAll();

        return $this->render('SolucelAdminBundle:RepairOrderDelivery:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new RepairOrderDelivery entity.
     *
     */
    public function newAction(Request $request)
    {
    	$this->get("services")->setVars('repairOrderDelivery');
		$session = new Session();
		
		/*
        $entity = new RepairOrderDelivery();
        $form   = $this->createCreateForm($entity);
		 
	
        return $this->render('SolucelAdminBundle:RepairOrderDelivery:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
		 * */
		
        $em = $this->getDoctrine()->getManager();
		$user = $session->get('user_logged');
		
		
		$arrayFilter = array();
		//
		$arrayFilter["filter_dates"] = $filter_dates = 1;
		$arrayFilter["filter_created_from"] = date("Y-m-d");
		$arrayFilter["filter_created_to"] = date("Y-m-d");
		
		$filter_created_from = date("d/m/Y");
		$filter_created_to = date("d/m/Y");
		//$select_operator = $user->getOperator() != NULL ? $user->getOperator()->getId() : 0;
		$select_operator = 0;
		$select_agency = 0;
		//$select_service_center = $user->getServiceCenter() != NULL ? $user->getServiceCenter()->getId() : 0;
		$select_service_center = 0;
		$select_brand = 0;
		$select_status = 0;
		//$select_status = 1;//Ingresado
		$ticket_search = "";
				
				
		$toAgency = 0;
		$fromAgency = 0;
		
		$toServiceCenter = 0;
		$fromServiceCenter = 0;
		
				
		///filter
		if(isset($_REQUEST["filter"])){
			/*
			print "<pre>";
			print_r($_REQUEST);die;
			 * */
			 
			$select_operator = $arrayFilter["filter_operator"] = $_REQUEST["select_operator"];
			$select_brand = $arrayFilter["filter_brand"] = $_REQUEST["select_brand"];
			$select_agency = $arrayFilter["filter_agency"] = $_REQUEST["select_agency"];
			$select_service_center = $arrayFilter["filter_service_center"] = $_REQUEST["select_service_center"];
			$select_status = $arrayFilter["filter_status"] = $_REQUEST["select_status"];
			$requestFrom = implode("-", array_reverse(explode("/", $_REQUEST["created_from"])));  
			$requestTo = implode("-", array_reverse(explode("/", $_REQUEST["created_to"])));
			$ticket_search = trim($_REQUEST["ticket_search"]);
			
			$toAgency = $_REQUEST["to_agency"];
			$fromAgency = $_REQUEST["from_agency"];
			
			$toServiceCenter = $_REQUEST["to_service_center"];
			$fromServiceCenter = $_REQUEST["from_service_center"];
			
			$arrayFilter["filter_created_from"] = $requestFrom;
			
			//var_dump($arrayFilter["filter_created_from"]);die;
			$filter_created_from = $_REQUEST["created_from"];
			
			$arrayFilter["filter_created_to"] = $requestTo;
			$filter_created_to = $_REQUEST["created_to"];
			
			$filter_dates = isset($_REQUEST["filter_dates"]) ? 1 : 0;
			$arrayFilter["filter_dates"] = $filter_dates;
			
			$ticket_search = $arrayFilter["ticket_search"] =  trim($ticket_search);
			
		}
		
		$entities = $em->getRepository('SolucelAdminBundle:RepairOrder')->filterDelivery($arrayFilter);
		
		//var_dump($entities);die;
		
        
        
        
		//filtros
		$agencies = $em->getRepository('SolucelAdminBundle:Agency')->findByEnabled(1);
		
		//print "<pre>";
		//
		//$user = $session->get('user_logged');
		//SERVICE CENTERS
		$userServiceCenter = $user->getServiceCenter();
		if($userServiceCenter != NULL){
			$service_centers = $em->getRepository('SolucelAdminBundle:ServiceCenter')->find($userServiceCenter->getId());	
		}
		else{
			$service_centers = $em->getRepository('SolucelAdminBundle:ServiceCenter')->findByEnabled(1);
		}
		
		//OPERATORS
		$userOperator = $user->getOperator();
		if($userOperator != NULL){
			$operators = $em->getRepository('SolucelAdminBundle:Operator')->find($userOperator->getId());
			//$operators = $em->getRepository('SolucelAdminBundle:Operator')->findBy(array("id" => $userOperator->getId()));
			//$operators = $operators[0];
			/*
			print "<pre>";
			var_dump($operators);die;*/
			

		}
		else{
			$operators = $em->getRepository('SolucelAdminBundle:Operator')->findByEnabled(1);
		}		
		
		
		//BRANDS	
		$userBrand = $user->getDeviceBrand();
		if($userBrand != NULL){
			$brands = $em->getRepository('SolucelAdminBundle:DeviceBrand')->find($userBrand->getId());	
		}
		else{
			$brands = $em->getRepository('SolucelAdminBundle:DeviceBrand')->findByEnabled(1);
		}					
		 
		/* 
		print "<pre>";
		var_dump($service_centers);die;*/
		
		//$service_centers = $em->getRepository('SolucelAdminBundle:ServiceCenter')->findByEnabled(1);
		$repair_status = $em->getRepository('SolucelAdminBundle:RepairStatus')->findAll();
		
		
        return $this->render('SolucelAdminBundle:RepairOrderDelivery:form.html.twig', array(
            'entities'       => $entities,
            'operators'       => $operators,
            'agencies'       => $agencies,
            'service_centers'       => $service_centers,
            'brands'       => $brands,
            'repair_status' => $repair_status,
            'filter_created_from' => $filter_created_from,
            'filter_created_to' => $filter_created_to,
            'filter_dates' => $filter_dates,
            'select_operator' => $select_operator,
            'select_brand' => $select_brand,
            'select_agency' => $select_agency,
            'select_service_center' => $select_service_center,
            'select_status' => $select_status,
            'ticket_search' => $ticket_search,
			'to_agency' => $toAgency,
			'from_agency' => $fromAgency,
			'to_service_center' => $toServiceCenter,
			'from_service_center' =>  $fromServiceCenter            
			
        ));		 
		 
    }


    /**
     * Creates a sending RepairOrderDelivery entity.
     *
     */
    public function sendAction(Request $request)
    {
    	$this->get("services")->setVars('repairOrderDelivery');
        $entity = new RepairOrderDelivery();
        $form   = $this->createCreateForm($entity);
		 
	
        return $this->render('SolucelAdminBundle:RepairOrderDelivery:formDelivery.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    } 

    /**
     * Finds and displays a RepairOrderDelivery entity.
     *
     */
    public function showAction($entity)
    {
        $deleteForm = $this->createDeleteForm($entity);

        return $this->render('solucel_admin_repairorderdelivery/show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing RepairOrderDelivery entity.
     *
     */
    public function editAction(Request $request, $id)
    {
    	
		$this->get("services")->setVars('repairOrderDelivery');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:RepairOrderDelivery')->find($id);
				
        $deleteForm = $this->createDeleteForm($entity);
		$editForm = $this->createEditForm($entity);
        
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirectToRoute('solucel_admin_repairorderdelivery_edit', array('id' => $id));
        }

        return $this->render('SolucelAdminBundle:RepairOrderDelivery:edit.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            
        ));
    }
	
    /**
     * Deletes a RepairOrderDelivery entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
    	$this->get("services")->setVars('repairOrderDelivery');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:RepairOrderDelivery')->find($id);		
        $form = $this->createDeleteForm($entity);
        $form->handleRequest($request);

        //if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SolucelAdminBundle:RepairOrderDelivery')->find($entity);


        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RepairOrderDelivery entity.');
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
                    return $this->redirectToRoute('solucel_admin_repairorderdelivery_index');
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
        return $this->redirectToRoute('solucel_admin_repairorderdelivery_index');
    }

    /**
     * Creates a form to delete a RepairOrderDelivery entity.
     *
     * @param RepairOrderDelivery The RepairOrderDelivery entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('solucel_admin_repairorderdelivery_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
	
	
	
	
    /**
     * Creates a new RepairOrderDelivery entity.
     *
     */
    public function createAction(Request $request)
    {
		$this->get("services")->setVars('repairOrderDelivery');
        $entity = new RepairOrderDelivery();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
		/*print "<pre>";
		var_dump($form->getErrorsAsString());die;
		 * */
		 
        if ($form->isValid()) {
        	$em = $this->getDoctrine()->getManager();

			
			//$entity->setCreatedAtValue();
			//$entity->setDeliveryDateSent(new \DateTime("now"));
			//$entity->setDevicePurchaseDate(new \DateTime(implode("-", array_reverse(explode("/", $_REQUEST["repair_order"]["devicePurchaseDate"]))))  );
			
            $em->persist($entity);
            $em->flush();


			$this->get('services')->flashSuccess($request);
            return $this->redirect($this->generateUrl('solucel_admin_repairorderdelivery_index'));
			 
        }
		/*
		else{
			print "FORMULARIO NO VALIDO";DIE;
		}
		 * */

        return $this->render('SolucelAdminBundle:RepairOrderDelivery:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a RepairOrderDelivery entity.
     *
     * @param RepairOrderDelivery $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm($entity)
    {
    	
		$this->get("services")->setVars('repairOrderDelivery');
    	$session = new Session();
				
    	//$this->setVars();
        $form = $this->createForm(RepairOrderDeliveryType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_repairorderdelivery_create'),
            'method' => 'POST',
            //'operator' => $session->get("user_operator"),            
            //'client' => $this->userLogged,
        ));



        return $form;
    }	
	

	
	
    /**
    * Creates a form to edit a RepairOrderDelivery entity.
    *
    * @param RepairOrderDelivery $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm($entity)
    {
    	
		$this->get("services")->setVars('repairOrderDelivery');
    	$session = new Session();
				
		
        $form = $this->createForm(RepairOrderDeliveryType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_repairorderdelivery_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            //'operator' => $session->get("user_operator"),            
            //'client' => $this->userLogged,
        ));


        return $form;
    }
	
	
    /**
     * Edits an existing RepairOrderDelivery entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
    	$this->get("services")->setVars('repairOrderDelivery');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:RepairOrderDelivery')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RepairOrderDelivery entity.');
        }

        $deleteForm = $this->createDeleteForm($entity->getId());
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

			$this->get('services')->flashSuccess($request);
            return $this->redirect($this->generateUrl('solucel_admin_repairorderdelivery_index', array('id' => $id)));
			 
        }

        return $this->render('SolucelAdminBundle:RepairOrderDelivery:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }	
	
	

    /**
     * Finds and prints a Order entity.
     *
     */
    public function printAction(Request $request, $id)
    {
    	
    	$session = new Session();
		$this->get("services")->setVars('repairOrderDelivery');
        $em = $this->getDoctrine()->getManager();
		
        $entity = $em->getRepository('SolucelAdminBundle:RepairOrderDelivery')->find($id);
				
        $deleteForm = $this->createDeleteForm($entity->getId());
		
		$editForm = $this->createEditForm($entity);
		
        
        $editForm->handleRequest($request);
		
		//get client
		//$client = $em->getRepository('SolucelAdminBundle:Client')->find($entity->getClient());
		
		
		//get accessories
		$accessories = $em->getRepository('SolucelAdminBundle:RepairOrderDeviceAccessory')->findByRepairOrder($id);
		
		
		
        return $this->render('SolucelAdminBundle:RepairOrderDelivery:print.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'accessories' => $accessories,
            'edit' => 1,
            'show' => 1,
            
        ));    
	}


    public function receiveAction(Request $request, $id)
    {
    	
    	$this->get("services")->setVars('repairOrderDelivery');
        $em = $this->getDoctrine()->getManager();
		
		//actualizar Order Delivery
        $entity = $em->getRepository('SolucelAdminBundle:RepairOrderDelivery')->find($id);
		$entity->setDeliveryStatus("Recibido");
		$entity->setDeliveryDateReceived(new \DateTime("now"));
				
		$em->persist($entity);
		$em->flush();
		
		
		//actualizar Orden de reparacion
		$objRepairOrder =  $em->getRepository('SolucelAdminBundle:RepairOrder')->find($entity->getRepairOrder()->getId());
		
		//var_dump($entity->getDeliveryToAgency());die;
		
		$objRepairOrder->setAgency($em->getRepository('SolucelAdminBundle:Agency')->find( $entity->getDeliveryToAgency() ));		
		$objRepairOrder->setServiceCenter($em->getRepository('SolucelAdminBundle:ServiceCenter')->find( $entity->getDeliveryToServiceCenter() ));
		
		$em->persist($objRepairOrder);
		$em->flush();
				
		
		
		
		$this->get('services')->flashSuccess($request);
        return $this->redirectToRoute('solucel_admin_repairorderdelivery_index');
    }	


}

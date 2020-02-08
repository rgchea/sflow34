<?php

namespace Solucel\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Session;


use Solucel\AdminBundle\Entity\RepairOrder;
use Solucel\AdminBundle\Entity\RepairOrderStatus;
use Solucel\AdminBundle\Entity\Client;
use Solucel\AdminBundle\Entity\RepairOrderDeviceDefect;
use Solucel\AdminBundle\Entity\RepairOrderDeviceAccessory;
use Solucel\AdminBundle\Entity\RepairOrderDeviceLocation;
use Solucel\AdminBundle\Entity\RepairOrderAdditionalField;


use Solucel\AdminBundle\Form\RepairOrderType;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Repair controller
 *
 */

class RepairOrderController extends Controller
{
	
	

    /**
     * Lists all RepairOrder entities.
     *
     */
    public function indexAction(Request $request)
    {

		$this->get("services")->setVars('repairOrder');
    	$session = new Session();
    	
		
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
		
		//## NUEVOS FILTROS
		//NOMBRE DE CLIENTE
		//NUMERO DE IMEI
		//NUMERO DE TELEFONO
		//NUMERO DE BOLETA
		
		$filter_client_name = $arrayFilter["filter_client_name"] = "";
		$filter_imei = $arrayFilter["filter_imei"] = "";
		$filter_phone_number = $arrayFilter["filter_phone_number"] = "";
		$filter_id = $arrayFilter["filter_id"] = "";

		
		
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
			
			$arrayFilter["filter_created_from"] = $requestFrom;
			
			//var_dump($arrayFilter["filter_created_from"]);die;
			$filter_created_from = $_REQUEST["created_from"];
			
			$arrayFilter["filter_created_to"] = $requestTo;
			$filter_created_to = $_REQUEST["created_to"];
			
			$filter_dates = isset($_REQUEST["filter_dates"]) ? 1 : 0;
			$arrayFilter["filter_dates"] = $filter_dates;
			
			//## NEW FILTERS
			$filter_client_name = $arrayFilter["filter_client_name"] = trim($_REQUEST["filter_client_name"]);
			$filter_imei = $arrayFilter["filter_imei"] = trim($_REQUEST["filter_imei"]);
			$filter_phone_number = $arrayFilter["filter_phone_number"] = trim($_REQUEST["filter_phone_number"]);
			$filter_id = $arrayFilter["filter_id"] = trim($_REQUEST["filter_id"]);			
			
		}
		
		
		
        //$entities = $em->getRepository('SolucelAdminBundle:RepairOrder')->getOrders();
        
        
		//filtros
		//$agencies = $em->getRepository('SolucelAdminBundle:Agency')->findByEnabled(1);
		$agencies = array();
		
		//print "<pre>";
		//
		//$user = $session->get('user_logged');
		//SERVICE CENTERS
		$userServiceCenter = $user->getServiceCenter();
		if($userServiceCenter != NULL){
			$service_centers = $em->getRepository('SolucelAdminBundle:ServiceCenter')->find($userServiceCenter->getId());
			$select_service_center = $arrayFilter["filter_service_center"] = $userServiceCenter->getId();	
		}
		else{
			$service_centers = $em->getRepository('SolucelAdminBundle:ServiceCenter')->findByEnabled(1);
		}
		
		
		
		//OPERATORS
		$userOperator = $user->getOperator();
		if($userOperator != NULL){
			$operators = $em->getRepository('SolucelAdminBundle:Operator')->find($userOperator->getId());
			$select_operator = $arrayFilter["filter_operator"] =  $userOperator->getId();
			//print $select_operator;die;
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
			$select_brand = $arrayFilter["filter_brand"] = $userBrand->getId();
		}
		else{
			$brands = $em->getRepository('SolucelAdminBundle:DeviceBrand')->findByEnabled(1);
		}	

		$entities = $em->getRepository('SolucelAdminBundle:RepairOrder')->filterOrders($arrayFilter);

		//print "<pre>";
		foreach ($entities as $key => $value){

            $objReplacemets = $em->getRepository('SolucelAdminBundle:RepairOrderDeviceReplacement')->findByRepairOrder($key);
            $tmpReplacements = "";
            if($objReplacemets){
                foreach ($objReplacemets as $replacement){
                    $r = $replacement->getDeviceReplacement()->getName();
                    $tmpReplacements .= $tmpReplacements == "" ? $r: ",".$r;
                }
            }
            else{
                $tmpReplacements = "";
            }

            $entities[$key]["replacements"] = $tmpReplacements;

        }
		 
		/* 
		print "<pre>";
		var_dump($service_centers);die;*/
		
		//$service_centers = $em->getRepository('SolucelAdminBundle:ServiceCenter')->findByEnabled(1);
		$repair_status = $em->getRepository('SolucelAdminBundle:RepairStatus')->findAll();
		
		
        return $this->render('SolucelAdminBundle:RepairOrder:index.html.twig', array(
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
            //NEW FILTERS
            'filter_client_name' => $filter_client_name,
            'filter_phone_number' => $filter_phone_number,
            'filter_imei' => $filter_imei,
            'filter_id' => $filter_id
            
			
        ));
    }


    /**
     * Lists all RepairOrder entities STATUS INGRESADO, ready_to_assign = 0
     *
     */
    public function receptionAction(Request $request)
    {

        $this->get("services")->setVars('repairOrder');
        $session = new Session();


        $em = $this->getDoctrine()->getManager();
        $user = $session->get('user_logged');

        $role = $user->getRole()->getName();

        //var_dump($role);die;
        //$role = 'cliente';
        if($role != "LOGISTICA" && $role != "ADMINISTRADOR"){
            throw new AccessDeniedException();//
        }


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

        //## NUEVOS FILTROS
        //NOMBRE DE CLIENTE
        //NUMERO DE IMEI
        //NUMERO DE TELEFONO
        //NUMERO DE BOLETA

        $filter_client_name = $arrayFilter["filter_client_name"] = "";
        $filter_imei = $arrayFilter["filter_imei"] = "";
        $filter_phone_number = $arrayFilter["filter_phone_number"] = "";
        $filter_id = $arrayFilter["filter_id"] = "";



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

            $arrayFilter["filter_created_from"] = $requestFrom;

            //var_dump($arrayFilter["filter_created_from"]);die;
            $filter_created_from = $_REQUEST["created_from"];

            $arrayFilter["filter_created_to"] = $requestTo;
            $filter_created_to = $_REQUEST["created_to"];

            $filter_dates = isset($_REQUEST["filter_dates"]) ? 1 : 0;
            $arrayFilter["filter_dates"] = $filter_dates;

            //## NEW FILTERS
            $filter_client_name = $arrayFilter["filter_client_name"] = trim($_REQUEST["filter_client_name"]);
            $filter_imei = $arrayFilter["filter_imei"] = trim($_REQUEST["filter_imei"]);
            $filter_phone_number = $arrayFilter["filter_phone_number"] = trim($_REQUEST["filter_phone_number"]);
            $filter_id = $arrayFilter["filter_id"] = trim($_REQUEST["filter_id"]);

        }



        //$entities = $em->getRepository('SolucelAdminBundle:RepairOrder')->getOrders();


        //filtros
        //$agencies = $em->getRepository('SolucelAdminBundle:Agency')->findByEnabled(1);
        $agencies = array();

        //print "<pre>";
        //
        //$user = $session->get('user_logged');
        //SERVICE CENTERS
        $userServiceCenter = $user->getServiceCenter();
        if($userServiceCenter != NULL){
            $service_centers = $em->getRepository('SolucelAdminBundle:ServiceCenter')->find($userServiceCenter->getId());
            $select_service_center = $arrayFilter["filter_service_center"] = $userServiceCenter->getId();
        }
        else{
            $service_centers = $em->getRepository('SolucelAdminBundle:ServiceCenter')->findByEnabled(1);
        }



        //OPERATORS
        $userOperator = $user->getOperator();
        if($userOperator != NULL){
            $operators = $em->getRepository('SolucelAdminBundle:Operator')->find($userOperator->getId());
            $select_operator = $arrayFilter["filter_operator"] =  $userOperator->getId();
            //print $select_operator;die;
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
            $select_brand = $arrayFilter["filter_brand"] = $userBrand->getId();
        }
        else{
            $brands = $em->getRepository('SolucelAdminBundle:DeviceBrand')->findByEnabled(1);
        }

        $arrayFilter["filter_ready_to_assign"] = 0;
        $entities = $em->getRepository('SolucelAdminBundle:RepairOrder')->filterOrders($arrayFilter);

        //print "<pre>";
        foreach ($entities as $key => $value){

            $objReplacemets = $em->getRepository('SolucelAdminBundle:RepairOrderDeviceReplacement')->findByRepairOrder($key);
            $tmpReplacements = "";
            if($objReplacemets){
                foreach ($objReplacemets as $replacement){
                    $r = $replacement->getDeviceReplacement()->getName();
                    $tmpReplacements .= $tmpReplacements == "" ? $r: ",".$r;
                }
            }
            else{
                $tmpReplacements = "";
            }

            $entities[$key]["replacements"] = $tmpReplacements;

        }

        /*
        print "<pre>";
        var_dump($service_centers);die;*/

        //$service_centers = $em->getRepository('SolucelAdminBundle:ServiceCenter')->findByEnabled(1);
        $repair_status = $em->getRepository('SolucelAdminBundle:RepairStatus')->findBy(array("id" => 1), array("id" => "ASC"));


        return $this->render('SolucelAdminBundle:RepairOrder:reception.html.twig', array(
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
            //NEW FILTERS
            'filter_client_name' => $filter_client_name,
            'filter_phone_number' => $filter_phone_number,
            'filter_imei' => $filter_imei,
            'filter_id' => $filter_id


        ));
    }


    ///guardar recepción de taller
    public function receptionSaveAction(Request $request){

        $this->get("services")->setVars('repairOrder');
        $session = new Session();


        $em = $this->getDoctrine()->getManager();
        $user = $session->get('user_logged');

        $role = $user->getRole()->getName();

        if($role != "LOGISTICA" && $role != "ADMINISTRADOR"){
            throw new AccessDeniedException();//
        }
        //print "<pre>";
        //var_dump($_REQUEST);die;

        if(isset($_REQUEST["orders"])){

            $myOrders = $_REQUEST["orders"];
            //15 = PENDIENTE DE PAPELERIA
            $objStatus = $em->getRepository("SolucelAdminBundle:RepairStatus")->find(15);
            foreach ($myOrders as $key => $value){

                $objOrder = $em->getRepository("SolucelAdminBundle:RepairOrder")->find(intval($key));
                if($objOrder){
                    if($value == "assign"){
                        $objOrder->setReadyToAssign(1);
                        $em->persist($objOrder);
                        $em->flush();

                    }
                    else{

                        $objOrder->setRepairStatus($objStatus);
                        ///order status begin
                        $objRepairOrderStatus = new RepairOrderStatus();
                        $objRepairOrderStatus->setRepairOrder($objOrder);
                        $objRepairOrderStatus->setRepairStatus($objStatus);
                        $objRepairOrderStatus->setCreatedAtValue();
                        $objRepairOrderStatus->setCreatedBy($user);

                        $em->persist($objOrder);
                        $em->persist($objRepairOrderStatus);
                        $em->flush();
                    }
                }
            }
        }


        $this->get('services')->flashSuccess($request);
        return $this->redirectToRoute('solucel_admin_repairorder_reception');


    }




    /**
     * Lists all RepairOrder entities STATUS INGRESADO, ready_to_assign = 0
     *
     */
    public function paperworkAction(Request $request)
    {

        $this->get("services")->setVars('repairOrder');
        $session = new Session();


        $em = $this->getDoctrine()->getManager();
        $user = $session->get('user_logged');

        $role = $user->getRole()->getName();

        //var_dump($role);die;
        //$role = 'cliente';
        if($role != "LOGISTICA" && $role != "ADMINISTRADOR"){
            throw new AccessDeniedException();//
        }


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

        //## NUEVOS FILTROS
        //NOMBRE DE CLIENTE
        //NUMERO DE IMEI
        //NUMERO DE TELEFONO
        //NUMERO DE BOLETA

        $filter_client_name = $arrayFilter["filter_client_name"] = "";
        $filter_imei = $arrayFilter["filter_imei"] = "";
        $filter_phone_number = $arrayFilter["filter_phone_number"] = "";
        $filter_id = $arrayFilter["filter_id"] = "";



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

            $arrayFilter["filter_created_from"] = $requestFrom;

            //var_dump($arrayFilter["filter_created_from"]);die;
            $filter_created_from = $_REQUEST["created_from"];

            $arrayFilter["filter_created_to"] = $requestTo;
            $filter_created_to = $_REQUEST["created_to"];

            $filter_dates = isset($_REQUEST["filter_dates"]) ? 1 : 0;
            $arrayFilter["filter_dates"] = $filter_dates;

            //## NEW FILTERS
            $filter_client_name = $arrayFilter["filter_client_name"] = trim($_REQUEST["filter_client_name"]);
            $filter_imei = $arrayFilter["filter_imei"] = trim($_REQUEST["filter_imei"]);
            $filter_phone_number = $arrayFilter["filter_phone_number"] = trim($_REQUEST["filter_phone_number"]);
            $filter_id = $arrayFilter["filter_id"] = trim($_REQUEST["filter_id"]);

        }



        //$entities = $em->getRepository('SolucelAdminBundle:RepairOrder')->getOrders();


        //filtros
        //$agencies = $em->getRepository('SolucelAdminBundle:Agency')->findByEnabled(1);
        $agencies = array();

        //print "<pre>";
        //
        //$user = $session->get('user_logged');
        //SERVICE CENTERS
        $userServiceCenter = $user->getServiceCenter();
        if($userServiceCenter != NULL){
            $service_centers = $em->getRepository('SolucelAdminBundle:ServiceCenter')->find($userServiceCenter->getId());
            $select_service_center = $arrayFilter["filter_service_center"] = $userServiceCenter->getId();
        }
        else{
            $service_centers = $em->getRepository('SolucelAdminBundle:ServiceCenter')->findByEnabled(1);
        }



        //OPERATORS
        $userOperator = $user->getOperator();
        if($userOperator != NULL){
            $operators = $em->getRepository('SolucelAdminBundle:Operator')->find($userOperator->getId());
            $select_operator = $arrayFilter["filter_operator"] =  $userOperator->getId();
            //print $select_operator;die;
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
            $select_brand = $arrayFilter["filter_brand"] = $userBrand->getId();
        }
        else{
            $brands = $em->getRepository('SolucelAdminBundle:DeviceBrand')->findByEnabled(1);
        }

        $entities = $em->getRepository('SolucelAdminBundle:RepairOrder')->filterOrders($arrayFilter);


        /*
        print "<pre>";
        var_dump($service_centers);die;*/

        //$service_centers = $em->getRepository('SolucelAdminBundle:ServiceCenter')->findByEnabled(1);
        $repair_status = $em->getRepository('SolucelAdminBundle:RepairStatus')->findBy(array("id" => 15), array("id" => "ASC"));


        return $this->render('SolucelAdminBundle:RepairOrder:paperwork.html.twig', array(
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
            //NEW FILTERS
            'filter_client_name' => $filter_client_name,
            'filter_phone_number' => $filter_phone_number,
            'filter_imei' => $filter_imei,
            'filter_id' => $filter_id


        ));
    }


    ///guardar pendientes de papeleria
    public function paperworkSaveAction(Request $request){

        $this->get("services")->setVars('repairOrder');
        $session = new Session();

        //var_dump($_REQUEST);die;

        $em = $this->getDoctrine()->getManager();
        $user = $session->get('user_logged');

        $role = $user->getRole()->getName();

        if($role != "LOGISTICA" && $role != "ADMINISTRADOR"){
            throw new AccessDeniedException();//
        }
        //print "<pre>";
        //var_dump($_REQUEST);die;

        if(isset($_REQUEST["comments"])){

            $myOrders = $_REQUEST["comments"];
            foreach ($myOrders as $key => $value){

                $objOrder = $em->getRepository("SolucelAdminBundle:RepairOrder")->find(intval($key));
                if($objOrder){
                    $comment = trim($value);
                    if(strlen($comment) >= 15 ){
                        $objOrder->setPaperWorkComment($comment);
                        $em->persist($objOrder);
                        $em->flush();

                    }
                }
            }
        }


        if(isset($_REQUEST["assign"])){
            $status = $em->getRepository('SolucelAdminBundle:RepairStatus')->find(1);

            foreach ($_REQUEST["assign"] as $key => $value){
                $objOrder = $em->getRepository("SolucelAdminBundle:RepairOrder")->find(intval($key));
                $objOrder->setRepairStatus($status);
                $objOrder->setReadyToAssign(1);
                $em->persist($objOrder);
                $em->flush();
            }
        }


        $this->get('services')->flashSuccess($request);
        return $this->redirectToRoute('solucel_admin_repairorder_paperwork');


    }


    /**
     * Creates a new RepairOrder entity.
     *
     */
    public function newAction(Request $request)
    {
    	$session = new Session();
    	$this->get("services")->setVars('repairOrderNew');
		$em = $this->getDoctrine()->getManager();
		
		
        $entity = new RepairOrder();
        $form   = $this->createCreateForm($entity);
		$user = $session->get('user_logged');		 
 
		
		$states = $em->getRepository('SolucelAdminBundle:State')->findAll();
				
        return $this->render('SolucelAdminBundle:RepairOrder:new.html.twig', array(
            'entity' => $entity,
            'states' => $states,
            'form' => $form->createView(),
        ));
    }

 

    /**
     * Finds and displays a User entity.
     *
     */
    public function showAction(Request $request, $id)
    {
    	$session = new Session();
		$this->get("services")->setVars('repairOrderShow');
        $em = $this->getDoctrine()->getManager();
		
		///DATOS DE ORDEN
        $entity = $em->getRepository('SolucelAdminBundle:RepairOrder')->find($id);
				
        $deleteForm = $this->createDeleteForm($entity);
		$editForm = $this->createEditForm($entity);
        
        $editForm->handleRequest($request);

		$states = $em->getRepository('SolucelAdminBundle:State')->findAll();
		//get client
		//$client = $em->getRepository('SolucelAdminBundle:Client')->find($entity->getClient());
		$client = $entity->getClient();
		//get defect
		$defects = $em->getRepository('SolucelAdminBundle:RepairOrderDeviceDefect')->findByRepairOrder($id);
		//get accessories
		$accessories = $em->getRepository('SolucelAdminBundle:RepairOrderDeviceAccessory')->findByRepairOrder($id);
		
		$fields = $em->getRepository('SolucelAdminBundle:RepairOrderAdditionalField')->findByRepairOrder($id);
		
		///REPARACION
		$objOrderFix = $em->getRepository('SolucelAdminBundle:RepairOrderFix')->findByRepairOrder($id);
		$objReplacements = $em->getRepository('SolucelAdminBundle:RepairOrderDeviceReplacement')->findByRepairOrder($id);
		$objOrderFixes = $em->getRepository('SolucelAdminBundle:RepairOrderDeviceFixType')->findByRepairOrder($id);
		
		if($objOrderFix != NULL){
			$objOrderFix = $objOrderFix[0];
			$objConfirmationLog = $em->getRepository('SolucelAdminBundle:RepairOrderFixConfirmationLog')->findByRepairOrderFix($objOrderFix->getId());
		}
		
		if($objOrderFix){
			$hasFix = 1;
		}
		else{
			$hasFix = 0;
		}		
		
		///CONTROL DE CALIDAD
		
		////chequear si ya pasó por control de calidad
		
		$objQualityControl = $em->getRepository('SolucelAdminBundle:RepairOrderQualityControl')->findByRepairOrder($id);
		$arrRegisters = array();
		$arrGroups = array();
		
		if(!$objQualityControl){
			$objQualityControl = NULL;
			$objQualityRegisters = NULL;
		}
		else{
			
			$objQualityControl = $objQualityControl[0];
			$objQualityRegisters = $em->getRepository('SolucelAdminBundle:RepairOrderQualityControlRegister')->findByRepairOrderQualityControl($objQualityControl->getId());
			
			
			if($objQualityRegisters){

				foreach ($objQualityRegisters as $register) {
					
					if($register){
						$group = $register->getQualityControlGroup()->getId();
						$subgroup = $register->getQualityControlSubGroup()->getId();
						
						if(!isset($arrRegisters[$group])){
							$arrRegisters[$group] = array();	
						}
						if(!isset($arrRegisters[$group][$subgroup])){
							$arrRegisters[$group][$subgroup] = array();
						}
						
						//print "X$subgroup";
						$arrRegisters[$group][$subgroup]["check"]  = $register->getQualityCheck();
						$arrRegisters[$group][$subgroup]["uncheck"] = $register->getQualityUncheck();
						$arrRegisters[$group][$subgroup]["not_apply"] = $register->getNotApply();
						
						
					}
				}				
			}
			

			
			$groups = $em->getRepository('SolucelAdminBundle:QualityControlGroup')->findAll();
			foreach ($groups as $group) {
				$subGroups = $em->getRepository('SolucelAdminBundle:QualityControlSubGroup')->findByQualityControlGroup($group);
				$arrGroups[$group->getId()] = array();
				$arrGroups[$group->getId()]["name"] = $group->getName();
				$arrGroups[$group->getId()]["id"] = $group->getId();
				$arrGroups[$group->getId()]["subgroups"] = array();
				
				foreach ($subGroups as $subgroup) {
					$arrGroups[$group->getId()]["subgroups"][$subgroup->getId()]["name"] = $subgroup->getName();
					$arrGroups[$group->getId()]["subgroups"][$subgroup->getId()]["id"] = $subgroup->getId();
				}
				
			}			
		}		
		
		//ENTREGA
		$repairOrderHistory = $em->getRepository('SolucelAdminBundle:RepairOrder')->getOrderHistory($id); 
		
		/*
		print "<pre>";
		var_dump($repairOrderHistory);die;
		 * */
		 
        return $this->render('SolucelAdminBundle:RepairOrder:show.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'defects' => $defects,
            'accessories' => $accessories,
            'fields' => $fields,
            'client' => $client,
            'edit' => 1,
            'show' => 1,
            'objQualityControl' => $objQualityControl,
            'arrRegisters' => $arrRegisters,
            'arrGroups' => $arrGroups,
            'orderHistory' => $repairOrderHistory,
            'hasFix' => $hasFix,
            'states' => $states,
            
        ));
		    
	}



    public function sketchAction(Request $request, $id)
    {
    	$session = new Session();
		$this->get("services")->setVars('repairOrderShow');
        $em = $this->getDoctrine()->getManager();
		
        $entity = $em->getRepository('SolucelAdminBundle:RepairOrder')->find($id);
				
        return $this->render('SolucelAdminBundle:RepairOrder:sketch.html.twig', array(
            'sketch' => $entity->getSketchpadData(),
            
        ));
		    
	}

    /**
     * Displays a form to edit an existing RepairOrder entity.
     *
     */
    public function editAction(Request $request, $id)
    {
    	$this->get("services")->setVars('repairOrderNew');
        $em = $this->getDoctrine()->getManager();
		
		
		
        $entity = $em->getRepository('SolucelAdminBundle:RepairOrder')->find($id);
		$states = $em->getRepository('SolucelAdminBundle:State')->findAll();
				
        $deleteForm = $this->createDeleteForm($entity);
		
		$editForm = $this->createEditForm($entity);
		
        
        $editForm->handleRequest($request);

		//get client
		//$client = $em->getRepository('SolucelAdminBundle:Client')->find($entity->getClient());
		$client = $entity->getClient();
		//get defect
		$defects = $em->getRepository('SolucelAdminBundle:RepairOrderDeviceDefect')->findByRepairOrder($id);
		$fix = $em->getRepository('SolucelAdminBundle:RepairOrderFix')->findByRepairOrder($id);
		if($fix){
			$hasFix = 1;
		}
		else{
			$hasFix = 0;
		}
		

		//get additional fields
		$fields = $em->getRepository('SolucelAdminBundle:RepairOrderAdditionalField')->findByRepairOrder($id);
		
		
		//get accessories
		$accessories = $em->getRepository('SolucelAdminBundle:RepairOrderDeviceAccessory')->findByRepairOrder($id);
		
        return $this->render('SolucelAdminBundle:RepairOrder:edit.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'defects' => $defects,
            'accessories' => $accessories,
            'fields' => $fields,
            'client' => $client,
            'hasFix' => $hasFix,
            'edit' => 1,
            'states' => $states
            
        ));
		
    }
	
    /**
     * Deletes a RepairOrder entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
    	$this->get("services")->setVars('repairOrder');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:RepairOrder')->find($id);		
        $form = $this->createDeleteForm($entity);
        $form->handleRequest($request);

        //if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SolucelAdminBundle:RepairOrder')->find($entity);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find RepairOrder entity.');
            }



			
            //$em->remove($entity);
            $entity->setEnabled(0);
			$entity->setUpdatedAtValue();
			$em->persist($entity);
            $em->flush();        		
			
        	
        //}
		
			$this->get('services')->flashSuccess($request);
	        return $this->redirectToRoute('solucel_admin_repairorder_index');
    }
	
    /**
     * Creates a form to delete a RepairOrder entity.
     *
     * @param RepairOrder The RepairOrder entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($entity)
    {
    	//print "llega2";die;
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('solucel_admin_repairorder_delete', array('id' => $entity->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
	
	
	
	
    /**
     * Creates a new RepairOrder entity.
     *
     */
    public function createAction(Request $request)
    {
    	
		
		/*
		print "<pre>";
		var_dump($_REQUEST);die;
		 * */
		
    	
    	$session = new Session();
    	$this->get("services")->setVars('repairOrderNew');
        $entity = new RepairOrder();
        $form   = $this->createCreateForm($entity);
		$user = $session->get('user_logged');		 
		
		
		//$this->get('request')->query->remove('email_suffix');
		
		$arrClient = $_REQUEST["client"];
		$arrDefect = isset($_REQUEST["defect"]) ? $_REQUEST["defect"] : array();
		
		$arrField = isset($_REQUEST["field"]) ? $_REQUEST["field"] : array();
		$arrAccessory = isset($_REQUEST["accessory"]) ? $_REQUEST["accessory"] : array(); 
		
		
		$request->request->remove("client");
		$request->request->remove("defect");
		$request->request->remove("accessory");
		$request->request->remove("field");
		/*
		print "<pre>";
		print_r($request);
		
		die;*/
		
        $entity = new RepairOrder();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
		 
        if ($form->isValid()) {
        	
			
            $em = $this->getDoctrine()->getManager();
			//var_dump($entity);die;
			
			
			///CLIENT
			if(intval($arrClient["id"]) != 0){
				//link client
				
				$entity->setClient($em->getRepository('SolucelAdminBundle:Client')->find($arrClient["id"]));
				
			}
			else{
				/*
				print "<pre>";
				var_dump($arrClient);die;
				 * */
				
				//create client
				$objClient = new Client();
				$objClient->setClientCode($arrClient["client_code"]);
				$objClient->setName($arrClient["name"]);
				$objClient->setLastName($arrClient["last_name"]);
				$objClient->setPhone($arrClient["phone"]);
				$objClient->setEmail($arrClient["email"]);
				$objClient->setContactPhone($arrClient["contact_phone"]);
				
				 
				$objClient->setState($em->getRepository('SolucelAdminBundle:State')->find($arrClient["state"]));
				$objClient->setEnabled(1);
				$objClient->setCreatedAtValue();//date("Y-m-d H:i:s")
				//$objClient->setCreatedAt(new \DateTime("now"));
				
				$objClient->setClientType($arrClient["type"]);
				$objClient->setNit($arrClient["nit"]);
				$objClient->setDpi($arrClient["dpi"]);
				
	            $em->persist($objClient);
	            $em->flush();			
				
				$entity->setClient($objClient);
				
			}			
			
			//default values begin
			$status = $em->getRepository('SolucelAdminBundle:RepairStatus')->findByName("INGRESADO");
			
			/*
			print "<pre>";
			print_r($status[0]);
			die;
			 * */
			
			$entity->setInvoceNumber(0);
			$entity->setPrice(0.00);
			$entity->setDeposit("");
			$entity->setFinishedAt(new \DateTime("now"));
			$entity->setCreatedBy($user);///usuario loggeado
			$entity->setDispatchPhotoPath("");
			$entity->setDevicePurchaseDate(new \DateTime(implode("-", array_reverse(explode("/", $_REQUEST["repair_order"]["devicePurchaseDate"]))))  );
			$entity->setEntryDate(new \DateTime(implode("-", array_reverse(explode("/", $_REQUEST["repair_order"]["entryDate"]))))  );
			$entity->setEstimatedDeliveryDate(new \DateTime(implode("-", array_reverse(explode("/", $_REQUEST["repair_order"]["estimatedDeliveryDate"]))))  );
			$entity->setRepairStatus($status[0]);
			
			$entity->setCreatedAtValue();
			$entity->setUpdatedAtValue();
			//default values end
			
            $em->persist($entity);
            $em->flush();			


			 ///order status begin
			$objRepairOrderStatus = new RepairOrderStatus();
			$objRepairOrderStatus->setRepairOrder($entity);
			$objRepairOrderStatus->setRepairStatus($status[0]);
			$objRepairOrderStatus->setCreatedAtValue();
			$objRepairOrderStatus->setCreatedBy($user);
			
            $em->persist($objRepairOrderStatus);
            $em->flush();		
			  		 
			 
			//$entity->setRepairOrderStatus($objRepairOrderStatus);
			///order status end
			
			//print "entra";die;
			///defects
			if(!empty($arrDefect)){
				
				foreach ($arrDefect as $key => $value) {
					$objDefect = new RepairOrderDeviceDefect();
					$objDefect->setRepairOrder($entity);
					$objDefect->setDeviceDefect($em->getRepository('SolucelAdminBundle:DeviceDefect')->find($value));
		            $em->persist($objDefect);
		            $em->flush();			
					
					
				}
				
				
			}
			
			///accessories
			if(!empty($arrAccessory)){
				foreach ($arrAccessory as $key => $value) {
					$objAccessory = new RepairOrderDeviceAccessory();
					$objAccessory->setRepairOrder($entity);
					$objAccessory->setDeviceAccessory($em->getRepository('SolucelAdminBundle:DeviceAccessory')->find($value));
		            $em->persist($objAccessory);
		            $em->flush();			
					
				}				
			}
			
			///
			
			
			///guadar campos adicionales
			///accessories
			if(!empty($arrField)){
			foreach ($arrField as $key => $value) {
				$objField = new RepairOrderAdditionalField();
				$objField->setRepairOrder($entity);
				$objField->setName($value["name"]);
				$objField->setValue($value["value"]);
	            $em->persist($objField);
	            $em->flush();			
				
			}					
			}
		
			
			///device location
			$objDeviceLocation = new RepairOrderDeviceLocation();
			$objDeviceLocation->setRepairOrder($entity);
			$deviceLocation = $em->getRepository('SolucelAdminBundle:DeviceLocation')->findByName("Centro de Servicio");
			$objDeviceLocation->setDeviceLocation($deviceLocation[0]);
			$objDeviceLocation->setCreatedAtValue();			
	        $em->persist($objDeviceLocation);
	        $em->flush();
			

			$this->get('services')->flashSuccess($request);
            //return $this->redirect($this->generateUrl('solucel_admin_user_index'));
            return $this->redirect($this->get('router')->generate('solucel_admin_homepage'));
	                        

        }		 


        return $this->render('SolucelAdminBundle:RepairOrder:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a RepairOrder entity.
     *
     * @param RepairOrder $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm($entity)
    {
    	//$this->setVars();
		$this->get("services")->setVars('repairOrder');
    	$session = new Session();    	
    	
        $form = $this->createForm(RepairOrderType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_repairorder_create'),
            'method' => 'POST',
            'brand' => $session->get("user_device_brand"),
            'center' => $session->get("user_service_center"),
            'operator' => $session->get("user_operator"),
            
            //'client' => $this->userLogged,
        ));


        return $form;
    }	
	
	
    /**
    * Creates a form to edit a RepairOrder entity.
    *
    * @param RepairOrder $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm($entity)
    {
    	
		//$this->get("services")->setVars('repairOrder');
    	$session = new Session();    	    	
    	
        $form = $this->createForm(RepairOrderType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_repairorder_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'brand' => $session->get("user_device_brand"),
            'center' => $session->get("user_service_center"),
            'operator' => $session->get("user_operator"),
            //'client' => $this->userLogged,
        ));


        return $form;
    }
	
	
    /**
     * Edits an existing RepairOrder entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
    	
		/*
		print "<pre>";
		var_dump($_REQUEST);die;*/
		
		$this->get("services")->setVars('repairOrderNew');
    	$session = new Session();
		
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:RepairOrder')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RepairOrder entity.');
        }

		 //////////////////////////
		$user = $session->get('user_logged');		 
		
		//$this->get('request')->query->remove('email_suffix');
		
		$arrClient = $_REQUEST["client"];
		$arrDefect = isset($_REQUEST["defect"]) ? $_REQUEST["defect"] : array();
		$arrField = isset($_REQUEST["field"]) ? $_REQUEST["field"] : array();
		$arrAccessory = isset($_REQUEST["accessory"]) ? $_REQUEST["accessory"] : array(); 
		
				
		
		$request->request->remove("client");
		$request->request->remove("defect");
		$request->request->remove("accessory");		
		
        $deleteForm = $this->createDeleteForm($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
		
        
		////
        if ($editForm->isValid()) {

			//////////////BEGIN
            $em = $this->getDoctrine()->getManager();
			//var_dump($entity);die;
			
			
			///CLIENT
			if(intval($arrClient["id"]) != 0){
				//link client
				
				$entity->setClient($em->getRepository('SolucelAdminBundle:Client')->find($arrClient["id"]));
				
			}
			else{
				//create client
				$objClient = new Client();
				$objClient->setName($arrClient["name"]);
				$objClient->setLastName($arrClient["last_name"]);
				$objClient->setPhone($arrClient["phone"]);
				$objClient->setState($em->getRepository('SolucelAdminBundle:State')->find($arrClient["state"]));
				$objClient->setEnabled(1);
				$objClient->setCreatedAtValue();//date("Y-m-d H:i:s")
				//$objClient->setCreatedAt(new \DateTime("now"));
				
				$objClient->setClientType($arrClient["type"]);
				$objClient->setNit($arrClient["nit"]);
				$objClient->setDpi($arrClient["dpi"]);
				
	            $em->persist($objClient);
	            $em->flush();			
				
				$entity->setClient($objClient);
				
			}			
			
			//default values begin
			//$status = $em->getRepository('SolucelAdminBundle:RepairStatus')->findByName("Ingresado");
			
			/*
			print "<pre>";
			print_r($status[0]);
			die;
			 * */
			
			$entity->setInvoceNumber(0);
			$entity->setPrice(0.00);
			$entity->setDeposit("");
			$entity->setFinishedAt(new \DateTime("now"));
			$entity->setCreatedBy($user);///usuario loggeado
			$entity->setDispatchPhotoPath("");
			
			$entity->setCreatedAtValue();
			$entity->setUpdatedAtValue();
			//default values end
			
            $em->persist($entity);
            $em->flush();			

			
			/*
			 ///order status begin
			$objRepairOrderStatus = new RepairOrderStatus();
			$objRepairOrderStatus->setRepairOrder($entity);
			$objRepairOrderStatus->setRepairStatus($status[0]);
			$objRepairOrderStatus->setCreatedAtValue();
			$objRepairOrderStatus->setCreatedBy($user);
			
            $em->persist($objRepairOrderStatus);
            $em->flush();	
			 * */	
			//$entity->setRepairOrderStatus($objRepairOrderStatus);
			///order status end
			
			//print "entra";die;
			///defects
			//remove old rows
			$em->getRepository('SolucelAdminBundle:RepairOrder')->removeDefects($entity->getId());
			
			foreach ($arrDefect as $key => $value) {
				$objDefect = new RepairOrderDeviceDefect();
				$objDefect->setRepairOrder($entity);
				$objDefect->setDeviceDefect($em->getRepository('SolucelAdminBundle:DeviceDefect')->find($value));
	            $em->persist($objDefect);
	            $em->flush();			
				
				
			}
			
			//fields remove old rows
			$em->getRepository('SolucelAdminBundle:RepairOrder')->removeFields($entity->getId());
			
			foreach ($arrField as $key => $value) {
				$objField = new RepairOrderAdditionalField();
				$objField->setRepairOrder($entity);
				$objField->setName($value["name"]);
				$objField->setValue($value["value"]);
	            $em->persist($objField);
	            $em->flush();			
				
				
			}

			
			//removel old rows
			$em->getRepository('SolucelAdminBundle:RepairOrder')->removeAccessories($entity->getId());
			///accessories
			foreach ($arrAccessory as $key => $value) {
				$objAccessory = new RepairOrderDeviceAccessory();
				$objAccessory->setRepairOrder($entity);
				$objAccessory->setDeviceAccessory($em->getRepository('SolucelAdminBundle:DeviceAccessory')->find($value));
	            $em->persist($objAccessory);
	            $em->flush();			
				
			}			
			///
			
			///device location
			/*
			$objDeviceLocation = new RepairOrderDeviceLocation();
			$objDeviceLocation->setRepairOrder($entity);
			$deviceLocation = $em->getRepository('SolucelAdminBundle:DeviceLocation')->findByName("Centro de Servicio");
			$objDeviceLocation->setDeviceLocation($deviceLocation[0]);
			$objDeviceLocation->setCreatedAtValue();			
	        $em->persist($objDeviceLocation);
	        $em->flush();
			 * */

			$this->get('services')->flashSuccess($request);
            return $this->redirect($this->generateUrl('solucel_admin_repairorder_index', array('id' => $id)));
			 			 
        }

		return $this->redirectToRoute('solucel_admin_repairorder_edit', array('id' => $id));		
		/*
        return $this->render('SolucelAdminBundle:User:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
		 * 
		 */
		 
		 
    }	

	
    public function agencyAction(Request $request)
    {
    	
		$em = $this->getDoctrine()->getManager();
		$agencies = $em->getRepository('SolucelAdminBundle:Agency')->findBy(array("operator" => intval($_REQUEST["operator_id"]), "enabled" => 1) );		
		
		$arrAgency = array();
		foreach ($agencies as $agency) {
			$arrAgency[$agency->getId()] = $agency->getName();
		}		
    	
		return new JsonResponse($arrAgency);
		
		 
    }	


	
    public function deviceModelAction(Request $request)
    {
    	
		$em = $this->getDoctrine()->getManager();
		$models = $em->getRepository('SolucelAdminBundle:DeviceModel')->findBy(array("deviceBrand"=> intval($_REQUEST["device_brand_id"])), array( "name" => "ASC"));		
		
		$arrDeviceModel = array();

		$strReturn = "";

		foreach ($models as $model) {
		    $selected = "";
		    if(intval($_REQUEST["isEdit"]) == 1){
                if($model->getId() == intval($_REQUEST["device_model_id"])){
                    $selected = ' selected="selected" ';
                }
            }

			//$arrDeviceModel[$model->getId()] = $model->getName();
            $strReturn .= '<option value="'.$model->getId().'"'.$selected.'>'.$model->getName().'</option>';
		}		

		/*
		print "<pre>";
		var_dump($arrDeviceModel);die;
		*/

		print $strReturn;die;
		//return new JsonResponse($arrDeviceModel);
		
		 
    }


    public function getRepairDaysAction(Request $request)
    {
    	
		$em = $this->getDoctrine()->getManager();
		$operator = $em->getRepository('SolucelAdminBundle:Operator')->find(intval($_REQUEST["operator_id"]));		
		
		return new JsonResponse($operator->getDaysToFixDevice());		
		 
    }	
	
	
	public function clientFindAction(Request $request){
		
       	
        $term = trim(strip_tags($request->get('term')));

        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SolucelAdminBundle:Client')->findClient($term); 
        $response = new JsonResponse();
        $response->setData($entities);

        return $response;
    }		
	
	
	public function addDefectAction(Request $request){
		
		$em = $this->getDoctrine()->getManager();
		$entities = $em->getRepository('SolucelAdminBundle:DeviceDefect')->findBy(array(), array("name" => "ASC"));		
		
		$strOption = "";		
		$count = intval($_REQUEST["count"])+1;
		
		foreach ($entities as $defect) {
			$strOption .= '<option value="'.$defect->getId().'">'.$defect->getName().'</option>';
		}		
		
		
		$tr	 = '<tr id="tr_repair_order_defect_'.$count.'">' .
					'<td>'.
						'<label class="required" for="repair_order_defect">Falla '.$count.'&nbsp;</label>'.'<span style="cursor:pointer" onclick="deleteDefect('.$count.');" class="label label-danger arrowed arrow-left">Eliminar</span>'.
						'<select id="repair_order_defect_'.$count.'" name="defect['.$count.']" class="select2-search">'.
							$strOption.							
						'</select>'.
					'</td>'.
					
				'</tr>';	
				
		print $tr;
		die;
		
		

	}
		
	
	public function addAccessoryAction(Request $request){
		
		$em = $this->getDoctrine()->getManager();
		$entities = $em->getRepository('SolucelAdminBundle:DeviceAccessory')->findBy(array(), array("name" => "ASC"));
				
		
		$strOption = "";		
		$count = intval($_REQUEST["count"]);
		
		foreach ($entities as $accessory) {
			$strOption .= '<option value="'.$accessory->getId().'">'.$accessory->getName().'</option>';
		}		
		
		$tr	 = '<tr id="tr_repair_order_accessory_'.$count.'">' .
					'<td>'.
						'<label class="required" for="repair_order_accessory">Accesorio '.$count.'&nbsp;</label>'.'<span style="cursor:pointer" onclick="deleteAccessory('.$count.');" class="label label-danger arrowed arrow-left">Eliminar</span>'.
						'<select id="repair_order_accessory_'.$count.'" name="accessory['.$count.']" class="select2-search">'.
							$strOption.							
						'</select>'.
					'</td>'.
					
				'</tr>';	
				
		print $tr;
		die;
				
		

	}

	public function checkEntryAction(Request $request){
		
		$em = $this->getDoctrine()->getManager();
		$result = $em->getRepository('SolucelAdminBundle:RepairOrder')->checkEntryType($_REQUEST["code"], $_REQUEST["type"]);
		
		//var_dump($result);die;		
		
		$response = array();
		//$response["result"] = $result;
		$response["result"] = $result["result"];
		if($result["result"] == 2) //re ingreso
		{
			//2018-08-29
			$response["entry_date"] = implode("/", array_reverse(explode("-", $result["date"])) );
			//var_dump($date);die;

            $response["count"] = $result["count"];
            $response["history"] = $result["history"];
            $response["imei"] = $result["imei"];
		}



		   	
		return new JsonResponse($response);
						
	}
		
    /**
     * Lists all RepairOrder entities to be assigned.
     *
     */
    public function assignAction(Request $request)
    {
    	
		//print "die";die;
		$this->get("services")->setVars('repairOrder');
    	$session = new Session();
    	
		
        $em = $this->getDoctrine()->getManager();
		
		$arrayFilter = array();
		//
		
		$arrayFilter["filter_assign"] = 1; 
		//$arrayFilter
		
		$role = $session->get('user_role');
		
		/*
		if($role == "ADMINISTRADOR"){
			$serviceCenter = $em->getRepository('SolucelAdminBundle:ServiceCenter')->filterOrders($arrayFilter);				
		}
		else{
			
		}
		 * */
		 
		$user = $session->get('user_logged');
		$userServiceCenter = $user->getServiceCenter();
		if($userServiceCenter != NULL){
			//el usuario tiene un centro de servicio asignado
			$userServiceCenterID = $userServiceCenter->getId();
			//$arrayFilter["filter_service_center"] = $userServiceCenterID;
			//$entities = $em->getRepository('SolucelAdminBundle:RepairOrder')->filterOrders($arrayFilter);
			$service_centers = $em->getRepository('SolucelAdminBundle:ServiceCenter')->find($userServiceCenterID);
			
				
		}
		else{
			//el usuario no tiene centro de servicio asignado y puede ver todos
			//$entities = $em->getRepository('SolucelAdminBundle:RepairOrder')->filterOrders($arrayFilter);
			$userServiceCenterID = 0;
			$service_centers = $em->getRepository('SolucelAdminBundle:ServiceCenter')->findBy(array("enabled" => 1), array("name" => "ASC"));
		}		 
		
        //$entities = $em->getRepository('SolucelAdminBundle:RepairOrder')->getOrders();
		

                

        return $this->render('SolucelAdminBundle:RepairOrder:assign.html.twig', array(
            //'entities'       => $entities,
            'service_center' => $userServiceCenterID,             
            'service_centers' => $service_centers
			
        ));
		
		
		

		
    }

    public function changeOrdersAction(Request $request)
    {
    	
		//print "die";die;
		$this->get("services")->setVars('repairOrder');
    	$session = new Session();
		 
		 		
        $em = $this->getDoctrine()->getManager();
		
		$arrayFilter = array();
		//
		
		//solo ordenes con estado INGRESADO
		$arrayFilter["filter_assign"] = 1;
        $arrayFilter["filter_ready_to_assign"] = 1;
		//$arrayFilter
		
		$role = $session->get('user_role');

		 
		$user = $session->get('user_logged');
		$service_center = intval($_REQUEST["service_center_id"]);
		
			
		$arrayFilter["filter_service_center"] = $service_center;
		$entities = $em->getRepository('SolucelAdminBundle:RepairOrder')->filterOrders($arrayFilter);
			//$service_centers = $em->getRepository('SolucelAdminBundle:ServiceCenter')->find($userServiceCenterID);

        return $this->render('SolucelAdminBundle:RepairOrder:orders.html.twig', array(
            'entities'       => $entities,
            //'service_center' => $service_center,             
            //'service_centers' => $service_centers
			
        ));
        
        
		die;
		
		
		

		
    }

	public function assignTecAction(Request $request){
		
		//print "die";die;
		$this->get("services")->setVars('repairOrder');
    	$session = new Session();
    	
        $em = $this->getDoctrine()->getManager();
		$user = $session->get('user_logged');

		
		//print "<pre>";
		//var_dump($_REQUEST);die;
		
		$orders = array();
		if(isset($_REQUEST["orders"])){
			if(!empty($_REQUEST["orders"])){
				$orders = $_REQUEST["orders"];		
			}
		}	 
		
		//consulta numero de ordenes a base de datos - enable - estado ingresadas
		//consulta de tecnicos a base de datos - enable
			//desplegar listado de tecnicos con X a la par para eliminarlos de la lista
		//tras haber depurado el listado de tecnicos - click en asignar
		//algoritmo de asignacion en php
			//arreglo de ordenes y arreglo de usuarios tecnicos {IDs}
			//sacar el tecnico con minimas ordenes asignadas y asignarle una
			//esto se repite hasta terminar
			//insert a la base de datos
			
		///listado de asignacion en assign twig
		//imprimir listados
		//se puede transferir una orden a otro usuario tecnico para evitar sobrecargas de trabajo.
		
		
		///submit last step
		if(isset($_REQUEST["assign_tec"])){
			//creacion en base de datos
			if(!empty($orders)){
				$orders = json_decode($_REQUEST["orders"], true);	
			}
			
			
			
			//print "<pre>";
			//var_dump($orders);die;
			
			$service_center = intval($_REQUEST["service_center"]);
			
			$tecs = $_REQUEST["tec"];
			$status = $em->getRepository("SolucelAdminBundle:RepairStatus")->findByName("ASIGNADO");
			$status = $status[0];
						
			$entities = $em->getRepository('SolucelAdminBundle:RepairOrder')->assignOrders($tecs, $user->getId(), $status->getId(), $service_center, $orders);
			//var_dump($entities);die;
			
			$this->get('services')->flashSuccess($request);
	        //return $this->redirectToRoute('solucel_admin_repairorder_assign');
	        
	        return $this->render('SolucelAdminBundle:RepairOrder:assignTecResume.html.twig', array(
	            'entities'       => $entities, 
	        ));		        
			
					
						
		}
		else{
			//listado de tecnicos a asginar ordenes
			/*
			print "<pre>";
			var_dump($_REQUEST);die;*/
				
			 
			$service_center = intval($_REQUEST["select_service_center"]); 
			
			//getRole Tecnicos
			$role = $em->getRepository("SolucelAdminBundle:Role")->findByName("TECNICO REPARADOR");
			$roleTec = $role[0];

			$roleAgency = $em->getRepository("SolucelAdminBundle:Role")->findByName("TECNICO AGENCIA");
			$roleTecAgency = $roleAgency[0];
			
						
			//tecnicos
			$tecs = $em->getRepository('SolucelAdminBundle:User')->findBy(array("role"=>array($roleTec->getId(), $roleTecAgency->getId()), "serviceCenter" => $service_center ,"enabled"=>1));
					
			

			//$orders_count = intval($_REQUEST["orders_count"]);
			
	        return $this->render('SolucelAdminBundle:RepairOrder:assignTec.html.twig', array(
	            'tecs'       => $tecs, 
				'orders'       => json_encode($orders) ,
				//'orders_count'       => $orders_count,
	        ));		

			
		}
		
		
	}


    /**
     * Finds and prints a Order entity.
     *
     */
    public function printAction(Request $request, $id)
    {
    	$session = new Session();
		$this->get("services")->setVars('repairOrder');
        $em = $this->getDoctrine()->getManager();
		
        $entity = $em->getRepository('SolucelAdminBundle:RepairOrder')->find($id);
		
		$fix = $em->getRepository('SolucelAdminBundle:RepairOrderFix')->findByRepairOrder($id);
		
		if(!$fix){
			$fix = false;
			$hasWarranty = false;
			$fixDetail = "";
			$fixingPrice = "0.00";
		}
		else{
			$fix = $fix[0];
			$hasWarranty = $fix->getHasWarranty();
			$fixDetail = $fix->getFixDetail();
			$fixingPrice = $fix->getFixingPrice();
		}
		
		$status = $em->getRepository('SolucelAdminBundle:RepairOrderStatus')->findByRepairOrder($id);
		
		$arrStatus = array();
		$deliveryHour = "";
		$repairmentHour = "";
		if($status){
			foreach ($status as $s) {
				//$arrStatus[$s->getRepairStatus()->getId()] =  $s->getCreatedAt()->format('Y-m-d H:i:s');
				if($s->getRepairStatus()->getId() == 11){
					$deliveryHour = $s->getCreatedAt()->format('Y-m-d H:i:s');
				}
				
				if($s->getRepairStatus()->getId() == 3){
					$repairmentHour = $s->getCreatedAt()->format('Y-m-d H:i:s');
				}
				
			}
		}
		
		//print "<pre>";
		//var_dump($arrStatus);die;


		//get client
		//$client = $em->getRepository('SolucelAdminBundle:Client')->find($entity->getClient());
		$client = $entity->getClient();
		//get defect
		//$defects = $em->getRepository('SolucelAdminBundle:RepairOrderDeviceDefect')->findByRepairOrder($id);
		//get accessories
		$accessories = $em->getRepository('SolucelAdminBundle:RepairOrderDeviceAccessory')->findByRepairOrder($id);
		
		
		if($accessories){
			//$accessories = $accessories[0];
			
		}
		 
		$strAccessories = "";
		foreach ($accessories as $acc) {
			$strAccessories .= $acc->getDeviceAccessory()->getName().", ";
		}
		//var_dump($accessories);die;
		///SPREAD BUNDLE
		/*
	    $html = $this->renderView('SolucelAdminBundle:RepairOrder:print.html.twig', array(
            'entity' => $entity,
            'fix' => $fix,
            //'form' => $editForm->createView(),
            'arrStatus' => $arrStatus,
            //'delete_form' => $deleteForm->createView(),
            //'defects' => $defects,
            'accessories' => $accessories,
            'client' => $client,
            ));

	    $pdfGenerator = $this->get('spraed.pdf.generator');
	
	    return new Response($pdfGenerator->generatePDF($html),
	                    200,
	                    array(
	                        'Content-Type' => 'application/pdf',
	                        'Content-Disposition' => 'inline; filename="out.pdf"'
	                    )
	    );	
		 */
	    $html = $this->renderView('SolucelAdminBundle:RepairOrder:print.html.twig', array(
            'entity' => $entity,
            //'fix' => $fix,
            'hasWarranty' => $hasWarranty,
            'fixDetail' => $fixDetail,
            'fixingPrice' => $fixingPrice,
            'deliveryHour' => $deliveryHour,
            'repairmentHour' => $repairmentHour,
            'arrStatus' => $arrStatus,
            'strAccessories' => $strAccessories,
            'client' => $client,
            ));
	  
	    $this->returnPDFResponseFromHTML($html, $entity);		 	
		
		/*
        return $this->render('SolucelAdminBundle:RepairOrder:print.html.twig', array(
            'entity' => $entity,
            'fix' => $fix,
            'form' => $editForm->createView(),
            'arrStatus' => $arrStatus,
            'delete_form' => $deleteForm->createView(),
            'defects' => $defects,
            'accessories' => $accessories,
            'client' => $client,
            'edit' => 1,
            'show' => 1,
            
        )); 
		 * */   
	}

	public function returnPDFResponseFromHTML($html, $entity){
	//set_time_limit(30); uncomment this line according to your needs
	// If you are not in a controller, retrieve of some way the service container and then retrieve it
	//$pdf = $this->container->get("white_october.tcpdf")->create('vertical', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	//if you are in a controlller use :
// output the HTML content
	//$pdf->writeHTML($html, true, false, true, false, '');	
	
	$pdf = $this->get("white_october.tcpdf")->create('vertical', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	$pdf->SetAuthor('Solucel');
	$pdf->SetTitle(('Impresión Boleta'));
	//$pdf->SetMargins(20,20,40, true);
	$pdf->AddPage();
	$pdf->writeHTML($html, true, false, true, false, '');
	
	
	// add a page
	//$pdf->AddPage();
	
	// set JPEG quality
	//$pdf->setJPEGQuality(75);
		
	// Image method signature:
	// Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
	
	// Image example with resizing
	//$imgPath = $this->get('templating.helper.assets')->getUrl('bundles/soluceladmin/img/phoneview.png');
	//$pdf->Image($imgPath, 0, 0, 200, '', 'PNG', '', '', true, 150, '', false, false, 1, false, false, false);
	
	////////
    //$html = $this->renderView('SolucelAdminBundle:RepairOrder:sketch.html.twig', array('sketch' => $entity->getSketchpadData()));		
	//$pdf->writeHTML($html, true, false, true, false, '');
	////////
	
	
	$filename = 'print';
	$pdf->Output($filename.".pdf",'I'); // This will output the PDF as a response directly
	
	}

	public function myFinishOrder($orderID, $guideOut){


        $this->get("services")->setVars('repairOrderFinish');
        $session = new Session();

        $em = $this->getDoctrine()->getManager();
        $user = $session->get('user_logged');

        $objOrder =  $em->getRepository('SolucelAdminBundle:RepairOrder')->find($orderID);

        $status = $em->getRepository('SolucelAdminBundle:RepairStatus')->findByName("ENTREGADO");
        //cambiar status de orden (insert - repiar_order_status)
        $objOrder->setRepairStatus($status[0]);
        $objOrder->setGuideOut($guideOut);
        $objOrder->setUpdatedAtValue();
        $em->persist($objOrder);
        $em->flush();

        //log status
        $objRepairStatus = new RepairOrderStatus();
        $objRepairStatus->setCreatedAtValue();
        $objRepairStatus->setRepairOrder($objOrder);
        $objRepairStatus->setRepairStatus($status[0]);
        $objRepairStatus->setCreatedBy($user);

        $em->persist($objRepairStatus);
        $em->flush();


    }


    public function finishOrderAction(Request $request)
    {
    	
		
		$this->get("services")->setVars('repairOrderFinish');
    	$session = new Session();
    	
		
        $em = $this->getDoctrine()->getManager();
		$user = $session->get('user_logged');




		if(isset($_REQUEST["finishBulk"])){

            //print "<pre>";
            //var_dump($_REQUEST);die;

            if(isset($_REQUEST["finish"])) {
                $arrOrders = $_REQUEST["finish"];
                $guideOut = trim($_REQUEST["guide_out_bulk"]);

                foreach ($arrOrders as $order => $value) {

                    $this->myFinishOrder($order, $guideOut);
                }

                $this->get('services')->flashSuccess($request);
            }
        }

		
		//////DONE ORDER BEGINS
		if(isset($_REQUEST["done"])){

            $guideOut = trim($_REQUEST["guide_out"]);
            $this->myFinishOrder(intval($_REQUEST["done"]), $guideOut);
            $this->get('services')->flashSuccess($request);

		}
		

		
		$arrayFilter = array();
		//filtro para ordenes FINALIZADAS
		$arrayFilter["filter_finish"] = 1;
		
		
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
			
			$requestFrom = implode("-", array_reverse(explode("/", $_REQUEST["created_from"])));  
			$requestTo = implode("-", array_reverse(explode("/", $_REQUEST["created_to"])));
			
			$arrayFilter["filter_created_from"] = $requestFrom;
			
			//var_dump($arrayFilter["filter_created_from"]);die;
			$filter_created_from = $_REQUEST["created_from"];
			
			$arrayFilter["filter_created_to"] = $requestTo;
			$filter_created_to = $_REQUEST["created_to"];
			
			$filter_dates = isset($_REQUEST["filter_dates"]) ? 1 : 0;
			$arrayFilter["filter_dates"] = $filter_dates;
			
		}
		
		$entities = $em->getRepository('SolucelAdminBundle:RepairOrder')->filterOrders($arrayFilter);
		
        //$entities = $em->getRepository('SolucelAdminBundle:RepairOrder')->getOrders();
        
        
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
		
		
		
        return $this->render('SolucelAdminBundle:RepairOrder:finish.html.twig', array(
            'entities'       => $entities,
            'operators'       => $operators,
            'agencies'       => $agencies,
            'service_centers'       => $service_centers,
            'brands'       => $brands,
            
            'filter_created_from' => $filter_created_from,
            'filter_created_to' => $filter_created_to,
            'filter_dates' => $filter_dates,
            'select_operator' => $select_operator,
            'select_brand' => $select_brand,
            'select_agency' => $select_agency,
            'select_service_center' => $select_service_center,
			
        ));
    }	


	public function addFieldAction(Request $request){
		
		
		////GROUP BY TYPE IN SELECT
		$count = intval($_REQUEST["count"]);

		$tr	 = '<tr id="tr_repair_order_field_'.$count.'">' .
					'<td>'.
						'<div class="form-group col-md-6">'.
							'<label class="required" for="">Nombre</label>'.'<span style="cursor:pointer" onclick="deleteField('.$count.');" class="label label-danger arrowed arrow-left">Eliminar</span>'.
							'<input type="text" class="form-control" id="repair_order_field_'.$count.'" name="field['.$count.'][name]" />'.
	                     '</div>'.
	                     '<div class="form-group col-md-6">'.
	                     
							'<label class="required" for="">Valor</label>'.
							'<input type="text" class="form-control" name="field['.$count.'][value]" />'.
						'</div>'.							
					'</td>'.
				'</tr><tr><td>&nbsp;</td></tr>';
				
		print $tr;
		die;
		
	}
	
	public function reassignTecAction(Request $request, $id){
		
		//print "die";die;
		$this->get("services")->setVars('repairOrder');
    	$session = new Session();
    	
        $em = $this->getDoctrine()->getManager();
		$user = $session->get('user_logged');
		//var_dump($user->getId());die;
		
		$orderID = intval($id); 
		$objOrder = $em->getRepository("SolucelAdminBundle:RepairOrder")->find($orderID);
		
		//print "<pre>";
		//var_dump($_REQUEST);die;
		
		//RE ASIGNAR TECNICO
		if(isset($_REQUEST["reassign_tec"])){
				
			$objOrderFix = $em->getRepository("SolucelAdminBundle:RepairOrderFix")->findByRepairOrder($orderID);
			if($objOrderFix){
				$objOrderFix =  $objOrderFix[0];
				$newTecID = intval($_REQUEST["tec"]);
				$objTec =  $em->getRepository("SolucelAdminBundle:User")->find($newTecID);
				$objOrderFix->setAssignedTo($objTec);
				$objOrderFix->setAssignedBy($user);
				$objOrderFix->setUpdatedAtValue();
				
				$em->persist($objOrderFix);
	            $em->flush();       
				
				$objOrder->setUpdatedAtValue();
				$em->persist($objOrder);
	            $em->flush();       
				
				
				$this->get('services')->flashSuccess($request);
		        return $this->redirectToRoute('solucel_admin_repairorder_index');
				
			}
			
		}
		else{
			
		
			//listado de tecnicos a asginar ordenes
			/*
			print "<pre>";
			var_dump($_REQUEST);die;*/
				
			
			$service_center = $objOrder->getServiceCenter()->getId(); 
			
			//getRole Tecnicos
			$role = $em->getRepository("SolucelAdminBundle:Role")->findByName("TECNICO REPARADOR");
			$roleTec = $role[0];
			
			$roleAgency = $em->getRepository("SolucelAdminBundle:Role")->findByName("TECNICO AGENCIA");
			$roleTecAgency = $roleAgency[0];
			
			
			//tecnicos
			$tecs = $em->getRepository('SolucelAdminBundle:User')->findBy(array("role"=>array($roleTec->getId(), $roleTecAgency->getId()), "serviceCenter" => $service_center ,"enabled"=>1));
					
			
	
			//$orders_count = intval($_REQUEST["orders_count"]);
			
	        return $this->render('SolucelAdminBundle:RepairOrder:reAssignTec.html.twig', array(
	            'tecs'       => $tecs, 
	            'order_id' => $orderID
				//'orders'       => json_encode($orders) ,
				//'orders_count'       => $orders_count,
	        ));				
			
		}
	

		
	}

	public function repairTrackingCheckAction(Request $request){
		
		
        return $this->render('SolucelAdminBundle:RepairOrder:clientPublicCheck.html.twig');
		
		
	}

	public function repairTrackingAction(Request $request){
		
		//print $id;die;
		//boleta
		//nombre cliente
		//operador
		//marca
		//status
		//modelo
		//reparacion
		//IMEI
		//detalle de reparacion
		
		

        $em = $this->getDoctrine()->getManager();
		//var_dump($user->getId());die;
		$arrReturn = array();
		
		if(!isset($_REQUEST["ticket"])){
			return $this->redirectToRoute('solucel_repair_trackingcheck');
		}
		$ticket = trim($_REQUEST["ticket"]);
		$orderID = 0;
		 
		$objOrder = $em->getRepository("SolucelAdminBundle:RepairOrder")->findByTicketNumber($ticket);
		if($objOrder){
			$objOrder = $objOrder[0];
			$orderID = $objOrder->getId();
		}
		else{
			PRINT "Boleta incorrecta...";die;
		}
		
		$arrReturn["ticket"] = $objOrder->getTicketNumber();
		$objClient = $objOrder->getClient();
		$arrReturn["client"] = $objClient->getName()." ".$objClient->getLastName();
		$arrReturn["operator"] = $objOrder->getOperator()->getName();
		$arrReturn["brand"] = $objOrder->getDeviceBrand()->getName();
		$arrReturn["model"] =  $objOrder->getDeviceModel()->getName();
		$arrReturn["status"] = $objOrder->getRepairStatus()->getName();
		$arrReturn["imei"] = $objOrder->getDeviceImei();
		$arrReturn["fix"] = "";
		$arrReturn["fixDetail"] = "";
		
		$objOrderFix = $em->getRepository("SolucelAdminBundle:RepairOrderFix")->findByRepairOrder($orderID);
		if($objOrderFix){
			$objOrderFix = $objOrderFix[0];
			$arrReturn["fixDetail"] = $objOrderFix->getFixDetail();
		}

		//repair_order_device_fix_type
		$objOrderFixType = $em->getRepository("SolucelAdminBundle:RepairOrderDeviceFixType")->findByRepairOrder($orderID);
		if($objOrderFixType){
			//$objOrderFixType = $objOrderFixType[0];
			foreach ($objOrderFixType as $fixType) {
				$arrReturn["fix"] .= $fixType->getDeviceFixType()->getName().", ";				
			}

		}
		 
		 
				
        return $this->render('SolucelAdminBundle:RepairOrder:clientPublic.html.twig', array(
            'return'       => $arrReturn, 
        ));			
		
		
	}	

}

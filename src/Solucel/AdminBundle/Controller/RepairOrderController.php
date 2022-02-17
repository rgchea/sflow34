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
use Solucel\AdminBundle\Entity\TimeLog;


use Solucel\AdminBundle\Form\RepairOrderType;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Time;

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
                    if($replacement->getDeviceReplacement()){
                        $r = $replacement->getDeviceReplacement()->getName();
                        $tmpReplacements .= $tmpReplacements == "" ? $r: ",".$r;
                    }
                    else{
                        $tmpReplacements = "";
                    }
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
            print "ACCESO DENEGADO";DIE;
            //throw new AccessDeniedException();//
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
                    if($replacement->getDeviceReplacement() != null){
                        $r = $replacement->getDeviceReplacement()->getName();
                        $tmpReplacements .= $tmpReplacements == "" ? $r: ",".$r;

                    }
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
            PRINT "ACCESO DENEGADO";DIE;
            //throw new AccessDeniedException();//
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
            PRINT "ACCESO DENEGADO";DIE;
            //throw new AccessDeniedException();//
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

        //aqui rchea
        $entities = $em->getRepository('SolucelAdminBundle:RepairOrder')->filterOrders($arrayFilter);

        /*
        foreach ($entities as $myOrder){
            var_dump($myOrder);
        }
        die;
        */


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
            PRINT "ACCESO DENEGADO";DIE;
            //throw new AccessDeniedException();//
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
		$objAdminSetting = $em->getRepository('SolucelAdminBundle:AdminSetting')->find(1);
        $entryEstimatedTime = intval($objAdminSetting->getEntryEstimatedTime());
        $dateNow = date('Y-m-d H:i:s');

				
        return $this->render('SolucelAdminBundle:RepairOrder:new.html.twig', array(
            'entity' => $entity,
            'states' => $states,
            'form' => $form->createView(),
            'entryEstimatedTime' => $entryEstimatedTime,
            'dateNow' => $dateNow,
            'new' => true
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
    	
		

		//print "<pre>";
		//var_dump($_REQUEST);die;

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
        $em = $this->getDoctrine()->getManager();

        //var_dump($entity);die;
        $myTicket = trim($_REQUEST["repair_order"]["ticketNumber"]);
        $objOrderDuplicate = $em->getRepository("SolucelAdminBundle:RepairOrder")->findOneByTicketNumber($myTicket);
        if($objOrderDuplicate){
            $this->get('services')->flashWarningCustom($request, "No fue posible guardar, ya que la boleta ".$myTicket. " existe previamente en el sistema");
            return $this->redirectToRoute('solucel_admin_repairorder_new');
        }

		 
        if ($form->isValid()) {

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
            //$entity->setEntryDate(new \DateTime(implode("-", array_reverse(explode("/", $_REQUEST["repair_order"]["entryDate"]))))  );
			$entity->setEstimatedDeliveryDate(new \DateTime(implode("-", array_reverse(explode("/", $_REQUEST["repair_order"]["estimatedDeliveryDate"]))))  );
			$entity->setRepairStatus($status[0]);

			$relapse = intval($_REQUEST["repair_order"]["relapse"]) == 0 ? NULL : intval($_REQUEST["repair_order"]["relapse"]);
			if($relapse != NULL){
                $objRelapse = $em->getRepository('SolucelAdminBundle:RepairOrder')->find($relapse);
                $entity->setRelapseRepairOrder($objRelapse);

            }

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


	        //LOG ENTRY TIME INTO TIME_LOG TABLE
            $objTimeLog = new TimeLog();
            $objTimeLog->setCreatedAtValue();
            $objTimeLog->setRepairOrder($entity);
            $objTimeLog->setAction("INGRESO");
            $objTimeLog->setUser($user);

            $from_time = strtotime($_REQUEST["entryTime"]);
            $dateNow = date("Y-m-d H:i:s");
            $to_time = strtotime($dateNow);
            $myMinutes = round(abs($to_time - $from_time) / 60, 0);//minutes
            $objTimeLog->setLogTimeMinutes($myMinutes);

            $em->persist($objTimeLog);
            $em->flush();

			$this->get('services')->flashSuccess($request);
            //return $this->redirect($this->generateUrl('solucel_admin_user_index'));
            return $this->redirect($this->get('router')->generate('solucel_admin_homepage'));
	                        

        }
        else{

            /*
            foreach($form->getErrors(true, false) as $er) {
                print_r($er->__toString());
            }
            die;
            */

            $this->get('services')->flashWarningCustom($request, "Formulario Inválido ");
        }

        //var_dump("entra");
        return $this->redirectToRoute('solucel_admin_repairorder_new');

        /*
        return $this->render('SolucelAdminBundle:RepairOrder:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
        */
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
            //'old_data' => 0
            
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
            //'old_data' => $entity->getOldData()
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

		$isOldData = intval($_REQUEST["isOldData"]);

		if($isOldData){
            $models = $em->getRepository('SolucelAdminBundle:DeviceModel')->findBy(array("deviceBrand"=> intval($_REQUEST["device_brand_id"])), array( "name" => "ASC"));
        }
		else{
            $models = $em->getRepository('SolucelAdminBundle:DeviceModel')->findBy(array("enabled" => 1,  "deviceBrand"=> intval($_REQUEST["device_brand_id"])), array( "name" => "ASC"));
        }

		
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
		$response["result"] = intval($result["result"]);

		$reEntryTypes = array(2,3);
		//2 reingreso
        //3 reincidencia
        $response["imei"] = "";
        $response["device_code_fab"] = "";
        $response["device_msn"] = "";
        $response["device_xcvr"] = "";
        $response["invoice_number"] = "";
        $response["device_purchase_date"] = "";


        if (in_array($response["result"], $reEntryTypes))
		{
			//2018-08-29
			$response["entry_date"] = implode("/", array_reverse(explode("-", $result["date"])) );
			//var_dump($date);die;

            $response["count"] = $result["count"];
            $response["history"] = $result["history"];
            $response["imei"] = $result["imei"];
            $response["id"] = $result["id"];
            $response["device_purchase_date"] = $result["device_purchase_date"];

            $objOrder = $em->getRepository('SolucelAdminBundle:RepairOrder')->find(intval($result["id"]));

            $response["device_code_fab"] = $objOrder->getDeviceCodeFab();
            $response["device_msn"] = $objOrder->getDeviceMsn();
            $response["device_xcvr"] = $objOrder->getDeviceXcvr();
            $response["invoice_number"] = $objOrder->getInvoiceNumber();

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


    public function batchAction(Request $request)
    {

        $this->get("services")->setVars('repairOrderBatch');
        $session = new Session();


        $em = $this->getDoctrine()->getManager();
        $user = $session->get('user_logged');

        $role = $user->getRole()->getName();

        //var_dump($role);die;
        //$role = 'cliente';
        if ($role != "ADMINISTRADOR") {
            PRINT "ACCESO DENEGADO";DIE;
            //throw new AccessDeniedException();//
        }

        if(isset($_REQUEST["loadSubmit"])){
            set_time_limit(60000);


            $csvData = array();
            $tmpName = $_FILES['file']['tmp_name'];
            //$csvAsArray = array_map('str_getcsv', file($tmpName));


            $file_handle = fopen($tmpName, 'r');
            while (!feof($file_handle) ) {
                $line_of_text[] = fgetcsv($file_handle, 1024);
            }
            fclose($file_handle);

            /*
            print "<pre>";
            var_dump($line_of_text) ;die;
             * */

            $error = "";

            foreach ($line_of_text as $key => $value) {


                //var_dump(count($value));die;

                ///check columns on row
                if(count($value) != 27){
                    continue;
                }

                if($key != 0){

                    //print "<pre>";
                    //var_dump($value);die;

                    //PRINT "ENTRA 1";DIE;
                    //**buscarlo en base de datos y sustituir con ID

                    //0 - boleta No//
                    //1 - guia entrada
                    //2 - operador// name match
                    //3 - agencia// name match
                    //4 - centro de servicio // name match
                    //5 - fecha ingreso // y-m-d
                        //Fecha Estimada de Entrega
                    //6 - código de cliente //client_code: CL00001
                    //7 - tipo de dispositivo //name match
                    //8 - plan //name match
                    //9 - marca //name match
                    //10- modelo - //name match
                    //11- color - //name match
                    //12 - fecha de compra del dispositivo // y-m-d
                    //13 - Número Asociado
                    //14 -  IMEI
                    //15 -  MSN
                    //16 - XCVR
                    //17 - Código de fabricación
                    //18 - Número de Factura
                        //tipo de ingreso //repair_entry_type_id
                    //19 - Ingreso por humedad // si-no
                    //20 - falla 1
                    //21 - falla 2
                    //22 - Descripción del problema
                    //23 - observaciones
                    //24 - estado //default 1 = INGRESADO

                    //--EXTRA FIELDS--//
                    //25 - accesorio 1
                    //26 - accesorio 2


                    //-- TECH FIELDS--//

                    //27 - garantía // si-no
                    //28 - detalle de reparación
                    //29 - reparación // name match
                    //30 - repuesto 1 // replacement_code
                    //31 - repuesto 2 // replacement_code
                    //32 - comentario sobre garantía

                    $ticketNumber = trim($value[0]);
                    $guideIn = trim($value[1]);
                    $operator = trim($value[2]);
                    $agency = trim($value[3]);
                    $serviceCenter = trim($value[4]);
                    $entryDate = trim($value[5]);
                    $clientCode = trim($value[6]);
                    $deviceType = trim($value[7]);
                    $plan = trim($value[8]);
                    $brand = trim($value[9]);
                    $model = trim($value[10]);
                    $color = trim($value[11]);
                    $devicePurchaseDate = trim($value[12]);
                    $PhoneNumber = trim($value[13]);
                    $imei = trim($value[14]);
                    $msn = trim($value[15]);
                    $xcvr = trim($value[16]);
                    $fabCode = trim($value[17]);
                    $invoiceNumber = trim($value[18]);
                    $humidity = trim(strtolower($value[19])) == "si" ? 1 : 0 ;
                    $failure1 = trim($value[20]);
                    $failure2 = trim($value[21]);
                    $deviceProblem = trim($value[22]);
                    $observations = trim($value[23]);
                    $status =  trim($value[24]);
                    $accessory1 = trim($value[25]);
                    $accessory2 = trim($value[26]);

                    $line = $key+1 . "<br/>";

                    //--DEVICE TYPE--//
                    $entity = $this->findEntity('Solucel\AdminBundle\Entity\DeviceType', $deviceType, false);
                    if(!empty($entity)){
                        $objDeviceType = $entity[0];
                    }
                    else{
                        $error .= "Error en Tipo de dispositivo {$deviceType}, línea ".$line;
                        continue;
                    }

                    //--STATUS--//
                    $entity = $this->findEntity('Solucel\AdminBundle\Entity\RepairStatus', $status, false);
                    if(!empty($entity)){
                        $objStatus = $entity[0];
                    }
                    else{
                        $error .= "Error en Status {$status}, línea ".$line;
                        continue;
                    }

                    //--CLIENT--//
                    $entity = $em->getRepository('SolucelAdminBundle:Client')->findOneByClientCode($clientCode);
                    if(!empty($entity)){
                        $objClient = $entity;
                    }
                    else{
                        $error .= "Error en código de cliente {$clientCode}, línea ".$line;
                        continue;
                    }

                    //--OPERATOR--//
                    $entity = $this->findEntity('Solucel\AdminBundle\Entity\DeviceColor', $color, false);
                    if(!empty($entity)){
                        $objColor = $entity[0];
                    }
                    else{
                        $error .= "Error en Operador {$operator}, línea ".$line;
                        continue;
                    }

                    //--OPERATOR--//
                    $entity = $this->findEntity('Solucel\AdminBundle\Entity\Operator', $operator);
                    if(!empty($entity)){
                        $objOperator = $entity[0];
                    }
                    else{
                        $error .= "Error en Operador {$operator}, línea ".$line;
                        continue;
                    }

                    //**BRAND**//
                    $entity = $this->findEntity('Solucel\AdminBundle\Entity\DeviceBrand', $brand);
                    if(!empty($entity)){
                        $objBrand = $entity[0];
                    }
                    else{
                        $error .= "Error en Marca {$brand}, línea ".$line;
                        continue;
                    }

                    //**MODEL**//
                    $entity = $this->findEntity('Solucel\AdminBundle\Entity\DeviceModel', $model);
                    if(!empty($entity)){
                        $objModel = $entity[0];
                    }
                    else{
                        $error .= "Error en Modelo {$model}, línea ".$line;
                        continue;
                    }

                    //**AGENCY**//
                    $entity = $this->findEntity('Solucel\AdminBundle\Entity\Agency', $agency);
                    if(!empty($entity)){
                        $objAgency = $entity[0];
                    }
                    else{
                        $error .= "Error en Agencia {$agency}, línea ".$line;
                        continue;
                    }

                    //**SERVICE CENTER**//
                    $entity = $this->findEntity('Solucel\AdminBundle\Entity\ServiceCenter', $serviceCenter);
                    if(!empty($entity)){
                        $objServiceCenter = $entity[0];
                    }
                    else{
                        $error .= "Error en Centro de servicio {$agency}, línea ".$line;
                        continue;
                    }

                    //**FAILURE 1**//
                    if($failure1 != ""){
                        $entity = $this->findEntity('Solucel\AdminBundle\Entity\DeviceDefect', $failure1, false);
                        if(!empty($entity)){
                            $objFailure1 = $entity[0];
                        }
                        else{
                            $error .= "Error en falla 1 {$failure1}, línea ".$line;
                            continue;
                        }
                    }

                    //**FAILURE 2**//
                    if($failure2 != ""){
                        $entity = $this->findEntity('Solucel\AdminBundle\Entity\DeviceDefect', $failure2, false);
                        if(!empty($entity)){
                            $objFailure2 = $entity[0];
                        }
                        else{
                            $error .= "Error en falla 2 {$failure2}, línea ".$line;
                            continue;
                        }
                    }

                    //**ACCESSORY 1**//
                    if($accessory1 != ""){
                        $entity = $this->findEntity('Solucel\AdminBundle\Entity\DeviceAccessory', $accessory1, false);
                        if(!empty($entity)){
                            $objAccessory1 = $entity[0];
                        }
                        else{
                            $error .= "Error en falla 1 {$accessory1}, línea ".$line;
                            continue;
                        }
                    }

                    //**ACCESSORY 2**//
                    if($accessory2 != ""){
                        $entity = $this->findEntity('Solucel\AdminBundle\Entity\DeviceAccessory', $accessory2, false);
                        if(!empty($entity)){
                            $objAccessory2 = $entity[0];
                        }
                        else{
                            $error .= "Error en falla 2 {$accessory2}, línea ".$line;
                            continue;
                        }
                    }

                    $checkOrder = $em->getRepository('SolucelAdminBundle:RepairOrder')->findOneByTicketNumber($ticketNumber);
                    if($checkOrder){
                        $objRepairOder = $checkOrder;
                        $isUpdate = 1;
                    }
                    else{
                        $objRepairOder = new RepairOrder();
                        $isUpdate = 0;
                    }

                    $objRepairOder->setCreatedBy($user);
                    $entryType = $em->getRepository('SolucelAdminBundle:RepairOrder')->findOneByDeviceImei($imei);
                    if(!$entryType){//INGRESO
                        //print "entra ingreso";die;
                        $objRepairOder->setRepairEntryType($em->getRepository('SolucelAdminBundle:RepairEntryType')->find(1));
                    }
                    else{//RE INGRESO
                        //print "entra RE ingreso";die;
                        if($isUpdate == 1){
                            //$objRepairOder->setRepairEntryType($em->getRepository('SolucelAdminBundle:RepairEntryType')->find(2));
                        }
                        else{
                            $objRepairOder->setRepairEntryType($em->getRepository('SolucelAdminBundle:RepairEntryType')->find(2));
                        }

                    }

                    $objRepairOder->setOperator($objOperator);
                    $objRepairOder->setAgency($objAgency);
                    $objRepairOder->setClient($objClient);
                    $objRepairOder->setDeviceBrand($objBrand);
                    $objRepairOder->setDeviceModel($objModel);
                    $objRepairOder->setDeviceType($objDeviceType);
                    $objRepairOder->setDeviceColor($objColor);
                    $objRepairOder->setServiceCenter($objServiceCenter);
                    $objRepairOder->setRepairStatus($objStatus);
                    $objRepairOder->setTicketNumber($ticketNumber);
                    $objRepairOder->setDevicePlan($plan);
                    $objRepairOder->setDeviceImei($imei);
                    $objRepairOder->setDeviceMsn($msn);
                    $objRepairOder->setDeviceXcvr($xcvr);
                    $objRepairOder->setDeviceCodeFab($fabCode);
                    $objRepairOder->setDeviceProblem($deviceProblem);
                    $objRepairOder->setDeviceObservation($observations);
                    $objRepairOder->setInvoiceNumber($invoiceNumber);

                    //
                    $objRepairOder->setDeviceBorrowedImei("N/A");
                    $objRepairOder->setPrice(0);
                    $objRepairOder->setDeposit("N/A");
                    $objRepairOder->setDevicePurchaseDate(new \DateTime($devicePurchaseDate) );

                    $daysToRepair = $objOperator->getDaysToFixDevice();
                    $estimatedDeliveryDate = date('Y-m-d', strtotime($entryDate. ' + '.$daysToRepair.' days'));
                    $objRepairOder->setEstimatedDeliveryDate(new \Datetime($estimatedDeliveryDate));
                    $objRepairOder->setEntryDate(new \Datetime($entryDate));
                    $objRepairOder->setHumidity($humidity);
                    $objRepairOder->setEnabled(1);
                    $objRepairOder->setDispatchPhotoPath("");
                    $objRepairOder->setPhoneNumber($PhoneNumber);
                    $objRepairOder->setGuideIn($guideIn);
                    $objRepairOder->setReadyToAssign(0);
                    $objRepairOder->setOldData(0);
                    $objRepairOder->setFinishedAt(new \DateTime("now"));

                    $objRepairOder->setCreatedAt(new \DateTime("now"));
                    $objRepairOder->setUpdatedAt(new \DateTime("now"));

                    $em->persist($objRepairOder);
                    //$em->flush();
                    
                    if($isUpdate == 0){
                        //failures
                        if(isset($objFailure1)){
                            $objDeviceDefect1 = new RepairOrderDeviceDefect();
                            $objDeviceDefect1->setRepairOrder($objRepairOder);
                            $objDeviceDefect1->setDeviceDefect($objFailure1);
                            $em->persist($objDeviceDefect1);
                            unset($objFailure1);
                        }

                        if(isset($objFailure2)){
                            $objDeviceDefect2 = new RepairOrderDeviceDefect();
                            $objDeviceDefect2->setRepairOrder($objRepairOder);
                            $objDeviceDefect2->setDeviceDefect($objFailure2);
                            $em->persist($objDeviceDefect2);
                            unset($objFailure2);

                        }

                        //accesories
                        if(isset($objAccessory1)){
                            $objDeviceAccessory1 = new RepairOrderDeviceAccessory();
                            $objDeviceAccessory1->setRepairOrder($objRepairOder);
                            $objDeviceAccessory1->setDeviceAccessory($objAccessory1);
                            $em->persist($objDeviceAccessory1);
                            unset($objAccessory1);
                        }

                        if(isset($objAccessory2)){
                            $objDeviceAccessory2 = new RepairOrderDeviceAccessory();
                            $objDeviceAccessory2->setRepairOrder($objRepairOder);
                            $objDeviceAccessory2->setDeviceAccessory($objAccessory2);
                            $em->persist($objDeviceAccessory2);
                            unset($objAccessory2);
                        }

                    }


                    //$em->flush();
                }
            }

            if($error != ""){
                $this->get('services')->flashWarningCustom($request, "<br/>".$error);
            }
            else{
                $em->flush();
                $this->get('services')->flashSuccess($request);
            }


            return $this->redirectToRoute('solucel_admin_repairorder_batch');
        }


        return $this->render('SolucelAdminBundle:RepairOrder:batch.html.twig', array());



    }


    public function findEntity($entity, $searchTerm, $enabled = true){

        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $obj = $qb->select('x')->from($entity, 'x')
            //$expr->neq('a.deleted', 1)
            //->where( $qb->expr()->eq('x.name', "'".$searchTerm."'") )
            ->where( 'x.name = :param_search' )
            //->where( $qb->expr()->eq('x.name', ':param_search') )
            ->setParameter('param_search', $searchTerm);
            ;

        if( $enabled){
            //$qb->andWhere('x.enabled = 1');
        }
        $result = $qb->getQuery()
            ->getResult();

        return $result;

    }

}


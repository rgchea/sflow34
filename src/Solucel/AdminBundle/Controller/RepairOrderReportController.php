<?php

namespace Solucel\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Session;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\JsonResponse;


class RepairOrderReportController extends Controller
{
	

	public function filter($request){
		
		$_REQUEST = $request;

        $session = new Session();		
        $em = $this->getDoctrine()->getManager();
		$user = $session->get('user_logged');
		
		$arrayFilter = array();
		//
		$arrayFilter["filter_dates"] = $filter_dates = 1;
		$arrayFilter["filter_created_from"] = date("Y-m-d");
		$arrayFilter["filter_created_to"] = date("Y-m-d");
		$arrayFilter["select_year"] = date("Y");
		
		
		$filter_created_from = date("d/m/Y");
		$filter_created_to = date("d/m/Y");
		//$select_operator = $user->getOperator() != NULL ? $user->getOperator()->getId() : 0;
		$select_operator = 0;
		$select_agency = 0;
		//$select_service_center = $user->getServiceCenter() != NULL ? $user->getServiceCenter()->getId() : 0;
		$select_service_center = 0;
		$select_brand = 0;
		$select_status = 0;
		$select_year = date("Y");
        $year_before = $select_year - 2;
        $arrayFilter["year_before"] = $year_before;
		//$select_status = 1;//Ingresado
        $arrayFilter["entry_type"] = 0;
        $entry_type = 0;
				
		
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

            $entry_type = isset($_REQUEST["entry_type"]) ? intval($_REQUEST["entry_type"]) : 0;
			$arrayFilter["entry_type"] = $entry_type;
			
			$arrayFilter["filter_created_from"] = $requestFrom;
			
			//var_dump($arrayFilter["filter_created_from"]);die;
			$filter_created_from = $_REQUEST["created_from"];
			
			$arrayFilter["filter_created_to"] = $requestTo;
			$filter_created_to = $_REQUEST["created_to"];
			
			$filter_dates = isset($_REQUEST["filter_dates"]) && 1 ? 1 : 0;
			$arrayFilter["filter_dates"] = $filter_dates;
			
			if(isset($_REQUEST["select_year"])){
				$select_year = $arrayFilter["select_year"] = $_REQUEST["select_year"];
				$year_before = $arrayFilter["year_before"] = $select_year - 2;	
			}
			
			
		}
		
		//filtros
		$agencies = $em->getRepository('SolucelAdminBundle:Agency')->findByEnabled(1);
		
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
		 

		$repair_status = $em->getRepository('SolucelAdminBundle:RepairStatus')->findAll(); 	
		
		//var_dump($arrayFilter);die;
        $entry_types = $em->getRepository('SolucelAdminBundle:RepairEntryType')->findBy(array("id" => array(2,3)));//reingreso,reincidencia
		
		//print "<pre>";
		//var_dump($arrayFilter);die;
		$arrReturn = array('arrayFilter' => $arrayFilter,
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
                                            'select_year' => $select_year,
                                            'year_before' => $year_before,
                                            'entry_type' => $entry_type,
                                            'entry_types' => $entry_types
							);
		
		return $arrReturn;
			
		
	}	
	
	public function assignedOrdersAction(){
		
 		//print "die";die;
		$this->get("services")->setVars('assignedOrders');
    	$session = new Session();
    			
        $em = $this->getDoctrine()->getManager();
		
		$user = $session->get('user_logged');
		//$userFilters = $em->getRepository('SolucelAdminBundle:User')->getUserFilters($user->getId());
		
		
		//service Center
		$serviceCenter =  $user->getServiceCenter();
		
		if($serviceCenter != NULL){
			$centers = $em->getRepository('SolucelAdminBundle:ServiceCenter')->findBy(array("id" => $serviceCenter->getId(), "enabled" => 1 ));
			$serviceCenter = $serviceCenter->getId();
			
		}
		else{
			$centers =  $em->getRepository('SolucelAdminBundle:ServiceCenter')->findByEnabled(1);	
			$serviceCenter = 0;
		}

		$service_center = isset($_REQUEST["filter"]) ? intval($_REQUEST["select_service_center"]) : intval($serviceCenter);

		//Operator
		$operator =  $user->getOperator();
		
		if($operator != NULL){
			$operators = $em->getRepository('SolucelAdminBundle:Operator')->findBy(array("id" => $operator->getId(), "enabled" => 1 ));
			$operator = $operator->getId();
			
		}
		else{
			$operators =  $em->getRepository('SolucelAdminBundle:Operator')->findByEnabled(1);	
			$operator = 0;
		}	
		
		$operator = isset($_REQUEST["filter"]) ? intval($_REQUEST["select_operator"]) : intval($operator);	
		
		
		//Brand
		$brand =  $user->getDeviceBrand();
		
		if($brand != NULL){
			$brands = $em->getRepository('SolucelAdminBundle:DeviceBrand')->findBy(array("id" => $brand->getId(), "enabled" => 1 ));
			$brand = $brand->getId();
			
		}
		else{
			$brands =  $em->getRepository('SolucelAdminBundle:DeviceBrand')->findByEnabled(1);	
			$brand = 0;
		}	
		
		$brand = isset($_REQUEST["filter"]) ? intval($_REQUEST["select_brand"]) : intval($brand);	
				
		///////////////////

		$entities = $em->getRepository('SolucelAdminBundle:RepairOrder')->getTecAssignedOrders(0, $service_center, $operator, $brand);
		
		
        //$entities = $em->getRepository('SolucelAdminBundle:RepairOrder')->getOrders();
        
       	
        return $this->render('SolucelAdminBundle:RepairOrderReport:assignedOrders.html.twig', array(
            'entities'       => $entities,            
            'centers'       => $centers,
            'service_center'       => $service_center,
            'operators'       => $operators,
            'operator'       => $operator,
            'brands'       => $brands,
            'brand'       => $brand,
			
        ));			
		
	}		

    /**
     * Lists all entryByBrandModel entities.
     *
     * 
     */
    
    
    
    public function entryByBrandModelAction(Request $request)
    {
		
		$this->get("services")->setVars('entryByBrandModel');
    	$session = new Session();
    	
		
        $em = $this->getDoctrine()->getManager();
		$user = $session->get('user_logged');
		       

		$arrayReturn = $this->filter($_REQUEST);		
		
		$entities = $em->getRepository('SolucelAdminBundle:RepairOrderFix')->filterOrdersEBBM($arrayReturn["arrayFilter"]);
		
		     
            $operators = $arrayReturn["operators"];
            $agencies = $arrayReturn['agencies'];
            $service_centers = $arrayReturn['service_centers'];
            $brands = $arrayReturn['brands'];
            $repair_status = $arrayReturn['repair_status'];
            $filter_created_from = $arrayReturn['filter_created_from'];
            $filter_created_to = $arrayReturn['filter_created_to'];
            $filter_dates = $arrayReturn['filter_dates'];
            $select_operator = $arrayReturn['select_operator'];
            $select_brand = $arrayReturn['select_brand'];
            $select_agency = $arrayReturn['select_agency'];
            $select_service_center = $arrayReturn['select_service_center'];
            $select_status = $arrayReturn['select_status'];
            	  
	  
        return $this->render('SolucelAdminBundle:RepairOrderReport:entryByBrandModel.html.twig', array(
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
            
			
        ));
    }
    
    /**
     * Lists all entryByYearOperations entities.
     *
     * 
     */
    
    
    
    public function entryByYearOperationsAction(Request $request)
    {
		
        
//            $_REQUEST["select_status"] = 0;       
            $_REQUEST["created_from"] = 0;
            $_REQUEST["created_to"] = 0;
            $this->get("services")->setVars('entryByYearOperations');
            $session = new Session();
    	
		
            $em = $this->getDoctrine()->getManager();
            $user = $session->get('user_logged');

			$_REQUEST["select_status"] = 0;
            $arrayReturn = $this->filter($_REQUEST);		

            $entities = $em->getRepository('SolucelAdminBundle:RepairOrderFix')->filterOrdersEBYO($arrayReturn["arrayFilter"]);
            
		     
            $operators = $arrayReturn["operators"];
            $agencies = $arrayReturn['agencies'];
            $service_centers = $arrayReturn['service_centers'];
            $brands = $arrayReturn['brands'];
            
            $filter_created_from = $arrayReturn['filter_created_from'];
            $filter_created_to = $arrayReturn['filter_created_to'];
            $filter_dates = $arrayReturn['filter_dates'];
            $select_operator = $arrayReturn['select_operator'];
            $select_brand = $arrayReturn['select_brand'];
            $select_agency = $arrayReturn['select_agency'];
            $select_service_center = $arrayReturn['select_service_center'];
            //$select_status = $arrayReturn['select_status'];
            $select_year = $arrayReturn['select_year'];
            $year_one_before = $select_year - 1;
            $year_two_before = $select_year - 2;
            
            	  
	  
        return $this->render('SolucelAdminBundle:RepairOrderReport:entryByYearOperations.html.twig', array(
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
            'select_year' => $select_year,
            'year_one_before' => $year_one_before,
            'year_two_before' => $year_two_before,
			
        ));
    }

    
    public function pendingStatusAction(Request $request){
		
		$this->get("services")->setVars('pendingStatus');
    	$session = new Session();
    	
		
        $em = $this->getDoctrine()->getManager();
		$user = $session->get('user_logged');
		       
		$_REQUEST["select_status"] = 0;
		//$_REQUEST["filter_dates"] = 0;
		
		
		$arrayReturn = $this->filter($_REQUEST);
				
		
		$entities = $em->getRepository('SolucelAdminBundle:RepairOrderFix')->filterPendingStatusOrders($arrayReturn["arrayFilter"]);
		
		     
            $operators = $arrayReturn["operators"];
            $agencies = $arrayReturn['agencies'];
            $service_centers = $arrayReturn['service_centers'];
            $brands = $arrayReturn['brands'];
            $repair_status = $arrayReturn['repair_status'];
            $filter_created_from = $arrayReturn['filter_created_from'];
            $filter_created_to = $arrayReturn['filter_created_to'];
            $filter_dates = $arrayReturn['filter_dates'];
            $select_operator = $arrayReturn['select_operator'];
            $select_brand = $arrayReturn['select_brand'];
            $select_agency = $arrayReturn['select_agency'];
            $select_service_center = $arrayReturn['select_service_center'];
            $select_status = $arrayReturn['select_status'];
            		
										
		
        return $this->render('SolucelAdminBundle:RepairOrderReport:pendingStatus.html.twig', array(
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
            
			
        ));		
	}


	public function outDateAction(Request $request){
		
		$this->get("services")->setVars('outDate');
    	$session = new Session();
    	
		
        $em = $this->getDoctrine()->getManager();
		$user = $session->get('user_logged');
		       
		$_REQUEST["select_status"] = 0;
		$arrayReturn = $this->filter($_REQUEST);		
		
		$entities = $em->getRepository('SolucelAdminBundle:RepairOrderFix')->filterOutDateOrders($arrayReturn["arrayFilter"]);
		
		     
            $operators = $arrayReturn["operators"];
            $agencies = $arrayReturn['agencies'];
            $service_centers = $arrayReturn['service_centers'];
            $brands = $arrayReturn['brands'];
            $repair_status = $arrayReturn['repair_status'];
            $filter_created_from = $arrayReturn['filter_created_from'];
            $filter_created_to = $arrayReturn['filter_created_to'];
            $filter_dates = $arrayReturn['filter_dates'];
            $select_operator = $arrayReturn['select_operator'];
            $select_brand = $arrayReturn['select_brand'];
            $select_agency = $arrayReturn['select_agency'];
            $select_service_center = $arrayReturn['select_service_center'];
            $select_status = $arrayReturn['select_status'];
            		
		
        return $this->render('SolucelAdminBundle:RepairOrderReport:outDate.html.twig', array(
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
            
			
        ));		
	}


	public function responseTimeAction(Request $request){

		$this->get("services")->setVars('responseTime');
    	$session = new Session();

        $em = $this->getDoctrine()->getManager();
		$user = $session->get('user_logged');
		   
		$_REQUEST["select_status"] = 0;       
		$_REQUEST["created_from"] = 0;
		$_REQUEST["created_to"] = 0;
		//$select_year = date("Y");
		
		$arrayReturn = $this->filter($_REQUEST);
		
		/*
		print "<pre>";
		var_dump($arrayReturn);die;
		 * 
		 */
		 
		$entities = $em->getRepository('SolucelAdminBundle:RepairOrderFix')->filterResponseTimeOrders($arrayReturn["arrayFilter"]);
		print "<pre>";
		var_dump($entities);die;
		 
		     
            $operators = $arrayReturn["operators"];
            $agencies = $arrayReturn['agencies'];
            $service_centers = $arrayReturn['service_centers'];
            $brands = $arrayReturn['brands'];
            $repair_status = $arrayReturn['repair_status'];
            $filter_created_from = $arrayReturn['filter_created_from'];
            $filter_created_to = $arrayReturn['filter_created_to'];
            $filter_dates = $arrayReturn['filter_dates'];
            $select_operator = $arrayReturn['select_operator'];
            $select_brand = $arrayReturn['select_brand'];
            $select_agency = $arrayReturn['select_agency'];
            $select_service_center = $arrayReturn['select_service_center'];
            $select_status = $arrayReturn['select_status'];
			$select_year = $arrayReturn['select_year'];
            		
		
        return $this->render('SolucelAdminBundle:RepairOrderReport:responseTime.html.twig', array(
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
            'select_year' => $select_year,
            
			
        ));	
		
	}
	

	public function techWorkAction(Request $request){
		
		$this->get("services")->setVars('techWork');
    	$session = new Session();
    	
		
        $em = $this->getDoctrine()->getManager();
		$user = $session->get('user_logged');
		       
		$_REQUEST["select_status"] = 0;
		$arrayReturn = $this->filter($_REQUEST);		
		
		$entities = $em->getRepository('SolucelAdminBundle:RepairOrderFix')->filterTechWork($arrayReturn["arrayFilter"]);
		
		/*
		print "<pre>";
		var_dump($entities);die;
		 * 
		 */

		     
            $operators = $arrayReturn["operators"];
            $agencies = $arrayReturn['agencies'];
            $service_centers = $arrayReturn['service_centers'];
            $brands = $arrayReturn['brands'];
            $repair_status = $arrayReturn['repair_status'];
            $filter_created_from = $arrayReturn['filter_created_from'];
            $filter_created_to = $arrayReturn['filter_created_to'];
            $filter_dates = $arrayReturn['filter_dates'];
            $select_operator = $arrayReturn['select_operator'];
            $select_brand = $arrayReturn['select_brand'];
            $select_agency = $arrayReturn['select_agency'];
            $select_service_center = $arrayReturn['select_service_center'];
            $select_status = $arrayReturn['select_status'];
            		
		
        return $this->render('SolucelAdminBundle:RepairOrderReport:techWork.html.twig', array(
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
            
			
        ));		
	}


	public function techWorkByLevelAction(Request $request){
		
		$this->get("services")->setVars('techWorkByLevel');
    	$session = new Session();
    	
		
        $em = $this->getDoctrine()->getManager();
		$user = $session->get('user_logged');
		       
		$_REQUEST["select_status"] = 0;
		$arrayReturn = $this->filter($_REQUEST);		
		
		$levels = $em->getRepository("SolucelAdminBundle:DeviceFixLevel")->findAll();
		
		$entities = $em->getRepository('SolucelAdminBundle:RepairOrderFix')->filterTechWorkByLevel($arrayReturn["arrayFilter"], $levels);
		
		
		//print "<pre>";
		//var_dump($entities);die;
		 

		     
            $operators = $arrayReturn["operators"];
            $agencies = $arrayReturn['agencies'];
            $service_centers = $arrayReturn['service_centers'];
            $brands = $arrayReturn['brands'];
            $repair_status = $arrayReturn['repair_status'];
            $filter_created_from = $arrayReturn['filter_created_from'];
            $filter_created_to = $arrayReturn['filter_created_to'];
            $filter_dates = $arrayReturn['filter_dates'];
            $select_operator = $arrayReturn['select_operator'];
            $select_brand = $arrayReturn['select_brand'];
            $select_agency = $arrayReturn['select_agency'];
            $select_service_center = $arrayReturn['select_service_center'];
            $select_status = $arrayReturn['select_status'];
            		
		
        return $this->render('SolucelAdminBundle:RepairOrderReport:techWorkByLevel.html.twig', array(
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
            'levels' => $levels
            
			
        ));		
	}


      
      

	public function reEntryRepairmentByTechAction(Request $request){
		
		$this->get("services")->setVars('reEntryRepairmentByTech');
    	$session = new Session();
    	
		
        $em = $this->getDoctrine()->getManager();
		$user = $session->get('user_logged');
		       
		$_REQUEST["select_status"] = 0;
		$arrayReturn = $this->filter($_REQUEST);		
		
		$entities = $em->getRepository('SolucelAdminBundle:RepairOrderFix')->reEntryRepairmentByTech($arrayReturn["arrayFilter"]);
		
		/*
		print "<pre>";
		var_dump($entities);die;
		 * 
		 */

		     
            $operators = $arrayReturn["operators"];
            $agencies = $arrayReturn['agencies'];
            $service_centers = $arrayReturn['service_centers'];
            $brands = $arrayReturn['brands'];
            $repair_status = $arrayReturn['repair_status'];
            $filter_created_from = $arrayReturn['filter_created_from'];
            $filter_created_to = $arrayReturn['filter_created_to'];
            $filter_dates = $arrayReturn['filter_dates'];
            $select_operator = $arrayReturn['select_operator'];
            $select_brand = $arrayReturn['select_brand'];
            $select_agency = $arrayReturn['select_agency'];
            $select_service_center = $arrayReturn['select_service_center'];
            $select_status = $arrayReturn['select_status'];
            		
		
        return $this->render('SolucelAdminBundle:RepairOrderReport:reEntryRepairmentByTech.html.twig', array(
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
            
			
        ));		
	}


	public function reEntryByUserAction(Request $request){
		
		$this->get("services")->setVars('reEntryByUser');
    	$session = new Session();
    	
		
        $em = $this->getDoctrine()->getManager();
		$user = $session->get('user_logged');
		       
		$_REQUEST["select_status"] = 0;
		$arrayReturn = $this->filter($_REQUEST);		
		
		$entities = $em->getRepository('SolucelAdminBundle:RepairOrderFix')->reEntryByUser($arrayReturn["arrayFilter"]);
		
		/*
		print "<pre>";
		var_dump($entities);die;
		 * 
		 */
 
        $operators = $arrayReturn["operators"];
        $agencies = $arrayReturn['agencies'];
        $service_centers = $arrayReturn['service_centers'];
        $brands = $arrayReturn['brands'];
        $repair_status = $arrayReturn['repair_status'];
        $filter_created_from = $arrayReturn['filter_created_from'];
        $filter_created_to = $arrayReturn['filter_created_to'];
        $filter_dates = $arrayReturn['filter_dates'];
        $select_operator = $arrayReturn['select_operator'];
        $select_brand = $arrayReturn['select_brand'];
        $select_agency = $arrayReturn['select_agency'];
        $select_service_center = $arrayReturn['select_service_center'];
        $select_status = $arrayReturn['select_status'];
        		
		
        return $this->render('SolucelAdminBundle:RepairOrderReport:reEntryByUser.html.twig', array(
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
            
        ));		
	}

	public function consolidatedReportAction(Request $request){

		ini_set('memory_limit', '-1'); // or you could use 1G
		
		$this->get("services")->setVars('consolidatedReport');
    	$session = new Session();
    	
		
        $em = $this->getDoctrine()->getManager();
		$user = $session->get('user_logged');

		       
		$_REQUEST["select_status"] = 0;
		$arrayReturn = $this->filter($_REQUEST);
		///var_dump($arrayReturn);die;
		
		if(isset($_REQUEST["submit"])){
			$entities = $em->getRepository('SolucelAdminBundle:RepairOrderFix')->consolidatedReport($arrayReturn["arrayFilter"]);	
		}
		else{
			$entities = array();
		}
		
		
		/*
		print "<pre>";
		var_dump($entities);die;
		 * 
		 */
 
        $operators = $arrayReturn["operators"];
        $agencies = $arrayReturn['agencies'];
        $service_centers = $arrayReturn['service_centers'];
        $brands = $arrayReturn['brands'];
        $repair_status = $arrayReturn['repair_status'];
        $filter_created_from = $arrayReturn['filter_created_from'];
        $filter_created_to = $arrayReturn['filter_created_to'];
        $filter_dates = $arrayReturn['filter_dates'];
        $select_operator = $arrayReturn['select_operator'];
        $select_brand = $arrayReturn['select_brand'];
        $select_agency = $arrayReturn['select_agency'];
        $select_service_center = $arrayReturn['select_service_center'];
        $select_status = $arrayReturn['select_status'];
        		
		
        return $this->render('SolucelAdminBundle:RepairOrderReport:consolidatedReport.html.twig', array(
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
            'entry_type' => $arrayReturn["entry_type"],
            'entry_types' => $arrayReturn["entry_types"],
            
        ));		
		
	}



	public function salesAction(Request $request){
		
		$this->get("services")->setVars('salesReport');
    	$session = new Session();
    	
		
        $em = $this->getDoctrine()->getManager();
		$user = $session->get('user_logged');
		       
		$_REQUEST["select_status"] = 0;
		$arrayReturn = $this->filter($_REQUEST);		
		
		$entities = $em->getRepository('SolucelAdminBundle:RepairOrderFix')->filterSales($arrayReturn["arrayFilter"]);
		
		
		//print "<pre>";
		//var_dump($entities);die;
		 
            $operators = $arrayReturn["operators"];
            $agencies = $arrayReturn['agencies'];
            $service_centers = $arrayReturn['service_centers'];
            $brands = $arrayReturn['brands'];
            $repair_status = $arrayReturn['repair_status'];
            $filter_created_from = $arrayReturn['filter_created_from'];
            $filter_created_to = $arrayReturn['filter_created_to'];
            $filter_dates = $arrayReturn['filter_dates'];
            $select_operator = $arrayReturn['select_operator'];
            $select_brand = $arrayReturn['select_brand'];
            $select_agency = $arrayReturn['select_agency'];
            $select_service_center = $arrayReturn['select_service_center'];
            $select_status = $arrayReturn['select_status'];
            		
		
        return $this->render('SolucelAdminBundle:RepairOrderReport:sales.html.twig', array(
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
			
        ));		
	}



	public function entryTypeAction(Request $request){
		
		$this->get("services")->setVars('entryTypeReport');
    	$session = new Session();
    	
		
        $em = $this->getDoctrine()->getManager();
		$user = $session->get('user_logged');
		       
		$_REQUEST["select_status"] = 0;
		$arrayReturn = $this->filter($_REQUEST);		
		
		$entities = $em->getRepository('SolucelAdminBundle:RepairOrderFix')->filterEntryType($arrayReturn["arrayFilter"]);
		
		
		//print "<pre>";
		//var_dump($entities);die;
		 
            $operators = $arrayReturn["operators"];
            $agencies = $arrayReturn['agencies'];
            $service_centers = $arrayReturn['service_centers'];
            $brands = $arrayReturn['brands'];
            $repair_status = $arrayReturn['repair_status'];
            $filter_created_from = $arrayReturn['filter_created_from'];
            $filter_created_to = $arrayReturn['filter_created_to'];
            $filter_dates = $arrayReturn['filter_dates'];
            $select_operator = $arrayReturn['select_operator'];
            $select_brand = $arrayReturn['select_brand'];
            $select_agency = $arrayReturn['select_agency'];
            $select_service_center = $arrayReturn['select_service_center'];
            $select_status = $arrayReturn['select_status'];
            		
		
        return $this->render('SolucelAdminBundle:RepairOrderReport:entryType.html.twig', array(
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
			
        ));		
	}	



	public function motorolaAction(Request $request){
		
		//echo date_format(date_create('2018-09-03 03:12:12'), 'c');die;
		$this->get("services")->setVars('dataFeedMoto');
    	$session = new Session();
    	//$this->get("services")->setVars('codeLoad');
        $em = $this->getDoctrine()->getManager();
		
		
		$userRole = $session->get('user_role');
		$userLogged = $session->get('user_logged');
        
		set_time_limit(2000000);
    	
    	
		$txtData = array();			
		 
 		$directory = __DIR__.'/../../../../web/uploads/motorolaDataFeed/';
		$filename = "Claims_File_Data_Feed-Solucel-GT.txt";
		

			$fp = fopen($directory.$filename, 'w');
			
			$arrHeader =  array();
			$arrHeader[]  = "Accident date";//NULL
			$arrHeader[]  = "Action Code";
			$arrHeader[]  = "Action Reason Code";
			$arrHeader[]  = "AWB In";//NULL
			$arrHeader[]  = "AWB Out";//NULL
			$arrHeader[]  = "Board Serial Number In";//NULL
			$arrHeader[]  = "Board Serial Number Out";//NULL
			$arrHeader[]  = "Claim ID";
			$arrHeader[]  = "ClaimLine ID";//NULL
			$arrHeader[]  = "Complaint Date";//NULL
			$arrHeader[]  = "Courier In";//NULL
			$arrHeader[]  = "Courier Out";//NULL
			$arrHeader[]  = "Customer Complaint Code - Primary";
			$arrHeader[]  = "Customer complaint code secondary";//NULL
			$arrHeader[]  = "Date In";
			$arrHeader[]  = "Date Out";
			$arrHeader[]  = "Delivery Date";//NULL
			$arrHeader[]  = "Delivery Number";//NULL
			$arrHeader[]  = "Document type";//NULL
			$arrHeader[]  = "Enduser country";
			$arrHeader[]  = "Enduser e-mail";
			$arrHeader[]  = "Enduser ID";
			$arrHeader[]  = "Enduser name";
			$arrHeader[]  = "Enduser phone number";
			$arrHeader[]  = "Enduser street";//NULL
			$arrHeader[]  = "Enduser town";
			$arrHeader[]  = "Enduser zip code";
			$arrHeader[]  = "Escalated Repair Type";//NULL
			$arrHeader[]  = "Escalated RSP";//NULL
			$arrHeader[]  = "Fault Code";
			$arrHeader[]  = "Field bulletin number";//NULL
			$arrHeader[]  = "IMEI 2 In";
			$arrHeader[]  = "IMEI 2 Out";
			$arrHeader[]  = "IMEI Number In";
			$arrHeader[]  = "IMEI Number Out";
			$arrHeader[]  = "Inbound Shipment Type";//NULL
			$arrHeader[]  = "Insurance start date";//NULL
			$arrHeader[]  = "Item code out";//NULL
			$arrHeader[]  = "Job Creation Date";//NULL
			$arrHeader[]  = "Mandatory";//NULL
			$arrHeader[]  = "Manufacture date";
			$arrHeader[]  = "Material Missing";//NULL
			$arrHeader[]  = "Material Number";
			$arrHeader[]  = "Material serial number";//NULL
			$arrHeader[]  = "OEM";
			$arrHeader[]  = "Outbound Shipment Type";//NULL
			$arrHeader[]  = "Payer";
			$arrHeader[]  = "Pickup Arranged Date";//NULL
			$arrHeader[]  = "Pickup Date";//NULL
			$arrHeader[]  = "POP date";
			$arrHeader[]  = "POP supplier";//NULL
			$arrHeader[]  = "Problem Found Code";
			$arrHeader[]  = "Problem found code secondary";//NULL
			$arrHeader[]  = "Product Code In";
			$arrHeader[]  = "Product Code Out";
			$arrHeader[]  = "Product Type";
			$arrHeader[]  = "Product version in";//NULL
			$arrHeader[]  = "Product version out";//NULL
			$arrHeader[]  = "Project";
			$arrHeader[]  = "Provider/Carrier";//NULL
			$arrHeader[]  = "QA Timepstamp";//NULL
			$arrHeader[]  = "Quantity Exchanged";//NULL
			$arrHeader[]  = "Quantity Missing";//NULL
			$arrHeader[]  = "Quantity Replaced";//NULL
			$arrHeader[]  = "Quotation End Date";//NULL
			$arrHeader[]  = "Quotation Start Date";//NULL
			$arrHeader[]  = "Quotation Status Code";//NULL
			$arrHeader[]  = "Reference designator number";//NULL
			$arrHeader[]  = "Remarks";//NULL
			$arrHeader[]  = "Repair Service Partner ID";//NULL
			$arrHeader[]  = "Repair Status Code";
			$arrHeader[]  = "Repair Timestamp";
			$arrHeader[]  = "Report date";//NULL
			$arrHeader[]  = "Return Date";//NULL
			$arrHeader[]  = "RMA Number";//NULL
			$arrHeader[]  = "Root Cause";//NULL
			$arrHeader[]  = "Second Status";//NULL
			$arrHeader[]  = "Serial Number In";
			$arrHeader[]  = "Serial Number Out";
			$arrHeader[]  = "Shipped From";//NULL
			$arrHeader[]  = "Shipped To";//NULL
			$arrHeader[]  = "Shop ID";
			$arrHeader[]  = "Shop In Date";//NULL
			$arrHeader[]  = "Shop Out Date";//NULL
			$arrHeader[]  = "Software In";//NULL
			$arrHeader[]  = "Software Out";
			$arrHeader[]  = "Solution Awaited Code";//NULL
			$arrHeader[]  = "Special project number";//NULL
			$arrHeader[]  = "Support Partner Ticket ID";//NULL
			$arrHeader[]  = "Technician ID";
			$arrHeader[]  = "Transaction Code";
			$arrHeader[]  = "Warranty Flag";
			$arrHeader[]  = "Warranty number";//NULL
			
					
			foreach ($arrHeader as $key => $value) {
				
				if($value == "Warranty number"){
					fputs($fp, $value);
				}
				else{
					fputs($fp, $value."\t");	
				}
				
					
			}
			fputs($fp, "\r\n");
			//$output = "Accident date\t Action Code\r\n";
			//$output .= "";
			
			
			$arrBody =  array();
			$arrBody["accident_date"] = NULL;
			$arrBody["action_code"] = NULL;//QUERY
			$arrBody["action_reason_code"]= NULL;//QUERY
			$arrBody["awb_in"]  = NULL;
			$arrBody["awb_out"]  = NULL;
			$arrBody["board_serial_number_in"]  = NULL;
			$arrBody["board_serial_number_out"]  = NULL;
			$arrBody["claim_id"] = NULL;//QUERY
			$arrBody["claimline_id"] = NULL;
			$arrBody["complaint_date"]  = NULL;
			$arrBody["courier_in"]  = NULL;
			$arrBody["courier_out"]  = NULL;
			$arrBody["customer_complaint_code_primary"] =  NULL;//QUERY
			$arrBody["customer_complaint_code_secondary"]  = NULL;
			$arrBody["date_in"] = "iso8601";//QUERY
			$arrBody["date_out"] = "iso8601";//QUERY
			$arrBody["delivery_date"] = NULL;
			$arrBody["delivery_number"]  = NULL;
			$arrBody["document_type"]  = NULL;
			$arrBody["enduser_country"] = NULL;//QUERY
			$arrBody["enduser_email"] = NULL;//QUERY
			$arrBody["enduser_id"] = NULL;//QUERY
			$arrBody["enduser_name"] = NULL;//QUERY
			$arrBody["enduser_phonenumber"] = NULL;//QUERY
			$arrBody["enduser_street"] = NULL;
			$arrBody["enduser_town"] = NULL;//QUERY
			$arrBody["enduser_zipcode"] = NULL;//QUERY
			$arrBody["escalated_repair_type"]  = NULL;
			$arrBody["escalated_rsp"]  = NULL;
			$arrBody["fault_code"] = NULL;//QUERY
			$arrBody["field_bulletin_number"]  = NULL;
			$arrBody["imei2_in"] = NULL;//QUERY
			$arrBody["imei2_out"] = NULL;//QUERY
			$arrBody["imei_number_in"] = NULL;//QUERY
			$arrBody["imei_number_out"] = NULL;//QUERY
			$arrBody["inbound_shipment_type"] = NULL;
			$arrBody["insurance_start_date"]  = NULL;
			$arrBody["item_code_out"]  = NULL;
			$arrBody["job_creation_date"]  =  NULL;
			$arrBody["mandatory"]  = NULL;
			$arrBody["manufacture_date"] = "iso8601";//QUERY
			$arrBody["material_missing"] = NULL;
			$arrBody["material_number"] = NULL;//QUERY
			$arrBody["material_serial_number"] = NULL;
			$arrBody["oem"] = NULL;//QUERY
			$arrBody["outbound_shipment_type"]  = NULL;
			$arrBody["payer"] = NULL;//QUERY
			$arrBody["pickup_arranged_date"]  = NULL;
			$arrBody["pickup_date"]  =  NULL;
			$arrBody["pop_date"] = "iso8601";//QUERY
			$arrBody["pop_supplier"]  = NULL;
			$arrBody["problem_found_code"] = NULL;//QUERY
			$arrBody["problem_found_code_secondary"]  = NULL;
			$arrBody["product_code_in"] = NULL;//QUERY
			$arrBody["product_code_out"] = NULL;//QUERY
			$arrBody["product_type"] = NULL;//QUERY
			$arrBody["product_version_in"]  = NULL;
			$arrBody["product_version_out"]  = NULL;
			$arrBody["project"] = NULL;//QUERY
			$arrBody["provider_carrier"]  = NULL;
			$arrBody["qa_timestamp"]  = NULL;
			$arrBody["quantity_exchanged"]  = NULL;
			$arrBody["quantity_missing"]  = NULL;
			$arrBody["quantity_replaced"]  = NULL;//QUERY
			$arrBody["quotation_end_date"]  = NULL;
			$arrBody["qotation_start_date"]  = NULL;
			$arrBody["quotation_status_code"]  = NULL;
			$arrBody["reference_designator_number"]  = NULL;
			$arrBody["remarks"]  = NULL;
			$arrBody["repair_service_partner_id"] = NULL;//QUERY
			$arrBody["repair_status_code"] = NULL;//QUERY
			$arrBody["repair_timestamp"] = "iso8601";//QUERY
			$arrBody["report_date"] = "iso8601";//QUERY
			$arrBody["return_date"]  = NULL;
			$arrBody["rma_number"]  = NULL;
			$arrBody["root_cause"]  = NULL;
			$arrBody["second_status"]  = NULL;
			$arrBody["serial_number_in"] = NULL;//QUERY
			$arrBody["serial_number_out"] = NULL;//QUERY
			$arrBody["shipped_from"]  = NULL;
			$arrBody["shipped_to"]  = NULL;
			$arrBody["shop_id"] = NULL;//QUERY
			$arrBody["shop_in_date"]  = NULL;
			$arrBody["shop_out_date"] = NULL;
			$arrBody["software_in"]  = NULL;
			$arrBody["software_out"] = NULL;//QUERY
			$arrBody["solution_awaited_code"]  = NULL;
			$arrBody["special_project_number"]  = NULL;
			$arrBody["support_partner_ticket_id"]  = NULL;
			$arrBody["technician_id"] = NULL;
			$arrBody["transaction_code"] = NULL;//QUERY
			$arrBody["warranty_flag"] = NULL;//QUERY
			$arrBody["warranty_number"] = NULL;		
			
			$entities = $em->getRepository('SolucelAdminBundle:RepairOrder')->getMotoDataFeed();
			
			foreach ($entities as $register) {
												
				foreach ($arrBody as $key => $value) {
	
					if( isset($register[$key]) ){
						
						if($value == "iso8601"){
							//fputs($fp, date_format(date_create(''.$register[$key]), 'c')."\t");
							fputs($fp, ''.$register[$key]." -06:00"."\t");
						}
						else{
							$data = $register[$key];
							if($data == NULL){
								$data = "NULL";
							}
							
							if($key == "warranty_number"){
								fputs($fp, $data);	
							}
							else{
								fputs($fp, $data."\t");
							}
							
							
								
						}					
						
					}
					else{
						
						if($key == "warranty_number"){
							fputs($fp, "NULL");
						}
						else{
							fputs($fp, "NULL\t");
						}
						
					}
				}	
					
				fputs($fp, "\r\n");
			}
			
			
			fclose($fp);

	        return $this->render('SolucelAdminBundle:RepairOrderReport:motorola.html.twig', array(
	            'download'       => true,            
				
	        ));	
	
		
	}



}

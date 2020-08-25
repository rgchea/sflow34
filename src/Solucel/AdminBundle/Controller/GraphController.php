<?php

namespace Solucel\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Session;


class GraphController extends Controller
{


    protected $em;
    protected $translator;
    protected $repairOrderRepository;
    private  $renderer;
    private $session;
    private $userLogged;
    private $role;


    // Set up all necessary variable
    protected function initialise()
    {
        $this->session = new Session();
        $this->em = $this->getDoctrine()->getManager();
        $this->repairOrderRepository = $this->em->getRepository('SolucelAdminBundle:RepairOrder');
        $this->translator = $this->get('translator');
        $this->renderer = $this->get('templating');
        $this->userLogged = $this->session->get('userLogged');
        $this->role = $this->session->get('userLogged')->getRole()->getName();

    }

    public function indexAction()
    {

        //print "entra";die;

        $this->get("services")->setVars('graph');
        $this->initialise();

        $today = date("Y-m-d");
        $time = strtotime($today.' -7 days');
        $dateWeek = date("Y-m-d", $time);
        $time = strtotime($today.' -30 days');
        $dateMonth = date("Y-m-d", $time);

        ///FILTERS
        /// operator
        /// brand
        $filterOperator = 0;
        $filterBrand = 0;

            //print "<pre>";var_dump($session->get("user_access"));die;
            $arrStats = array();
            $arrTechs = array();

            if(isset($_REQUEST["submit"])){
                $dateFrom = trim($_REQUEST["dateFrom"]);
                $dateTo = trim($_REQUEST["dateTo"]);
                $filterOperator = intval($_REQUEST["filter_operator"]);
                $filterBrand = intval($_REQUEST["filter_brand"]);
            }
            else{
                $dateTo = date("Y-m-d");
                $time = strtotime($dateTo.' -30 days');
                //$time = strtotime($dateTo.' -600 days');
                $dateFrom = date("Y-m-d", $time);
            }
            //orders_by_tech

            $arrTechs = $this->em->getRepository("SolucelAdminBundle:User")->getTopByOrders($dateFrom, $dateTo, $filterOperator, $filterBrand);

            $strOrdersByTech = "";
            $strOrdersByTechLevels = "";

            $arrTechsIn = array();
            foreach ($arrTechs as $tech){
                $tmpString = '{name: "'.$tech["tech_name"].'",y: '.$tech["orders"].', drilldown: "'.$tech["tech_id"].'"}';
                $strOrdersByTech .= $strOrdersByTech == "" ? $tmpString : ",".$tmpString;

                array_push($arrTechsIn, $tech["tech_id"]);
            }

            //print "<pre>";
            //var_dump($arrTechsIn);die;

            $arrTechsLevels = $this->em->getRepository("SolucelAdminBundle:User")->getOrdersByLevel($dateFrom, $dateTo, $arrTechsIn, $filterOperator, $filterBrand);
            //print "<pre>";
            //var_dump($arrTechsLevels);die;

            if($arrTechsLevels){
                foreach ($arrTechsLevels as $key => $techs){

                    $myData = "";
                    foreach ($techs as $level){
                        //var_dump($service);die;
                        $tmpLevel = '["'.$level["name"].'",'.$level["count"].']';
                        $myData .= $myData == "" ? $tmpLevel : ','.$tmpLevel;
                    }

                    $tmpData = '{type: "pie",name: "'.$level["tech"].'",id: "'.$key.'",data: ['.$myData.']}';
                    $strOrdersByTechLevels .= $strOrdersByTechLevels == "" ? $tmpData : ",".$tmpData;
                }

            }
            else{
                $strOrdersByTechLevels = "";
            }


            ////REPARACIONES POR STATUS => PIE CHART
            $strOrdersByStatus = "";

            $arrOrdersByStatus = $this->em->getRepository("SolucelAdminBundle:RepairOrder")->getCountByStatus($dateFrom, $dateTo, $filterOperator, $filterBrand);
            //print "<pre>";
            //var_dump($arrOrdersByStatus);die;

            if($arrOrdersByStatus){
                foreach ($arrOrdersByStatus as $key => $status){

                    $myData = "";
                    //var_dump($service);die;
                    $strTmp = "{ name: '".$status["name"]."', y: ".$status["myCount"].", sliced: true }";

                    $strOrdersByStatus .= $strOrdersByStatus == "" ? $strTmp : ",".$strTmp;
                }

            }
            else{
                $strOrdersByStatus = "";
            }

            ////REPARACIONES POR NIVEL => PIE CHART

            $strOrdersBySLevel = "";

            $arrOrdersByLevel = $this->em->getRepository("SolucelAdminBundle:RepairOrder")->getPercentageByLevel($dateFrom, $dateTo, $filterOperator, $filterBrand);
            //print "<pre>";
            //var_dump($arrOrdersByStatus);die;

            if($arrOrdersByLevel){
                foreach ($arrOrdersByLevel as $key => $level){

                    $myData = "";
                    //var_dump($service);die;
                    $strTmp = "{ name: '".$level["name"]."', y: ".$level["myCount"].", sliced: true }";

                    $strOrdersBySLevel .= $strOrdersBySLevel == "" ? $strTmp : ",".$strTmp;
                }

            }
            else{
                $strOrdersBySLevel = "";
            }


            //REINCIDENCIA POR TECNICO GRAFICA BARRAS
            $strRelapseByTech = "";

            $arrRelapseByTech = $this->em->getRepository("SolucelAdminBundle:User")->getTopByRelapseOrders($dateFrom, $dateTo, $filterOperator, $filterBrand);
            //print "<pre>";
            //var_dump($arrOrdersByStatus);die;

            if(!empty($arrRelapseByTech)){
                foreach ($arrRelapseByTech as $key => $tech){
                    //['Shanghai', 24.2],
                    $myData = "";
                    //var_dump($service);die;
                    $strTmp = "['".$tech["username"]."', ".$tech["myCount"]."]";

                    $strRelapseByTech .= $strRelapseByTech == "" ? $strTmp : ",".$strTmp;
                }

            }
            else{
                $strRelapseByTech = "";
            }


            ///GET RELAPSE BY YEAR
            $strRelapseByYear = "";

            $arrRelapseByYear = $this->em->getRepository("SolucelAdminBundle:RepairOrder")->getRelapseByYear($filterOperator, $filterBrand);
            //print "<pre>";
            //var_dump($arrOrdersByStatus);die;

            $strRelapseByYearQuantity = "";
            $strRelapseByYearPercentage = "";
            if(!empty($arrRelapseByYear)){
                $strRelapseByYearQuantity = implode(",", $arrRelapseByYear["quantity"]);
                $strRelapseByYearPercentage = implode(",", $arrRelapseByYear["percentage"]);
            }


            ////RESPONSE TIME GRAPH
            //$myYear = date('Y');
            $myYear = "2018";
            $arrFilter = array("select_year" => $myYear);
            $arrFilter["filter_operator"] = $filterOperator;
            $arrFilter["filter_brand"] = $filterBrand;
            $arrResponseTime = $this->em->getRepository('SolucelAdminBundle:RepairOrderFix')->filterResponseTimeOrders($arrFilter);

            //print "<pre>";
            //var_dump($arrResponseTime);die;

            $myResponseTmp = array();
            $myResponseDaysTmp = array(0 => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0);

            $strResponseTime = "";

            foreach ($myResponseDaysTmp as $responseDay => $value){

                $tmpData = "";
                for($x = 1; $x<=12; $x++){
                    $data = $arrResponseTime[$x][$responseDay];
                    $tmpData .= $tmpData == "" ?  $data : ",".$data;
                }

                $dayData = "{name: '".$responseDay." días',data: [".$tmpData."]}";
                $strResponseTime .= $strResponseTime == "" ? $dayData : ",".$dayData;

            }

            //print "<pre>";
            //var_dump($strResponseTime);die;

            //SPEEDOMETER GAUGE
            //speedometerResponseTimeOrders
            if($filterOperator != 0){
                $arrSpeedometerOnTime = $this->em->getRepository("SolucelAdminBundle:RepairOrder")->speedometerResponseTimeOrders($dateFrom, $dateTo, $filterOperator, $filterBrand);
            }
            else{
                $arrSpeedometerOnTime = array();
            }


            //print "<pre>";
            //var_dump($arrSpeedometer);die;

            $arrStats["arrTechs"] = $arrTechs;
            $arrStats["strOrdersByTech"] = $strOrdersByTech;
            $arrStats["strOrdersByTechLevels"] = $strOrdersByTechLevels;
            $arrStats["strOrdersByStatus"] = $strOrdersByStatus;
            $arrStats["strRelapseByTech"] = $strRelapseByTech;
            $arrStats["strRelapseByYearQuantity"] = $strRelapseByYearQuantity;
            $arrStats["strRelapseByYearPercentage"] = $strRelapseByYearPercentage;
            $arrStats["strResponseTime"] = $strResponseTime;
            $arrStats["arrSpeedometerOnTime"] = $arrSpeedometerOnTime;


            print "<pre>";
            var_dump($arrStats["strRelapseByYearPercentage"]);
            var_dump($arrStats["strRelapseByYearQuantity"]);
            die;

            //$objCountry = $this->em->getRepository("BackendAdminBundle:GlobalCountry")->find(1);

            $operators = $this->em->getRepository('SolucelAdminBundle:Operator')->findBy(array("enabled" => 1), array("name" => "ASC"));
            $brands = $this->em->getRepository('SolucelAdminBundle:DeviceBrand')->findBy(array("enabled" => 1), array("name" => "ASC"));

            return $this->render('SolucelAdminBundle:Graph:index.html.twig', array(

                'role' => $this->role,
                'user' => $this->userLogged,
                'arrStats' => $arrStats,
                'dateFrom' => $dateFrom,
                'dateTo' => $dateTo,
                'operators' => $operators,
                'brands' => $brands,
                'filterOperator' => $filterOperator,
                'filterBrand' => $filterBrand

            ));

    }



}

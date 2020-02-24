<?php

namespace Solucel\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Session;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


class TimeLogController extends Controller
{


    protected $entityManager;
    protected $translator;
    protected $repository;

    // Set up all necessary variable
    protected function initialise()
    {
        $this->entityManager = $this->getDoctrine()->getManager();
        $this->repository = $this->entityManager->getRepository('SolucelAdminBundle:TimeLog');
        $this->translator = $this->get('translator');
    }


    public function listAction(Request $request)
    {

        $this->get("services")->setVars('timeLog');

        return $this->render('SolucelAdminBundle:TimeLog:index.html.twig');

    }


    public function listDatatablesAction(Request $request)
    {

        $this->get("services")->setVars('timeLog');
        $em = $this->getDoctrine()->getManager();

        // Set up required variables
        $this->initialise();

        // Get the parameters from DataTable Ajax Call
        if ($request->getMethod() == 'POST')
        {
            $draw = intval($request->request->get('draw'));
            $start = $request->request->get('start');
            $length = $request->request->get('length');
            $search = $request->request->get('search');
            $orders = $request->request->get('order');
            $columns = $request->request->get('columns');


            $arrDate["start"] = $request->request->get('start_date');
            $arrDate["end"] = $request->request->get('end_date');

            $arrDate["start"] =  trim($arrDate["start"]) != "" ? trim($arrDate["start"]). " 00:00:00" : trim($arrDate["start"]);
            $arrDate["end"] =  trim($arrDate["end"]) != "" ? trim($arrDate["end"]). " 23:59:59" : trim($arrDate["start"]);
        }
        else // If the request is not a POST one, die hard
            die;

        // Process Parameters

        // Orders
        foreach ($orders as $key => $order)
        {
            // Orders does not contain the name of the column, but its number,
            // so add the name so we can handle it just like the $columns array
            $orders[$key]['name'] = $columns[$order['column']]['name'];
        }

        // Further filtering can be done in the Repository by passing necessary arguments
        if(trim($arrDate["start"]) != "" && trim($arrDate["end"]) != ""){
            $otherConditions = $arrDate;
        }
        else{
            $otherConditions = null;
        }


        // Get results from the Repository

        $results = $em->getRepository('SolucelAdminBundle:TimeLog')->getRequiredDTData($start, $length, $orders, $search, $columns, $otherConditions);

        // Returned objects are of type Town
        $objects = $results["results"];
        // Get total number of objects
        $total_objects_count = $em->getRepository('SolucelAdminBundle:TimeLog')->count(array());
        // Get total number of results
        $selected_objects_count = count($objects);
        // Get total number of filtered data
        $filtered_objects_count = $results["countResult"];

        // Construct response
        $response = '{
            "draw": '.$draw.',
            "recordsTotal": '.$total_objects_count.',
            "recordsFiltered": '.$filtered_objects_count.',
            "data": [';

        $i = 0;

        foreach ($objects as $key => $entity)
        {
            $response .= '["';

            $j = 0;
            $nbColumn = count($columns);
            foreach ($columns as $key => $column)
            {
                // In all cases where something does not exist or went wrong, return -
                $responseTemp = "-";

                switch($column['name'])
                {
                    case 'id':
                        {
                            $responseTemp = $entity->getId();

                            break;
                        }
                    case 'username':
                        {
                            $responseTemp = $entity->getUser()->getUsername();

                            // Do this kind of treatments if you suspect that the string is not JS compatible
                            $responseTemp = htmlentities(str_replace(array("\r\n", "\n", "\r"), ' ', $responseTemp));

                            break;
                        }

                    case 'action':
                    {
                        $responseTemp = $entity->getAction();
                        break;
                    }

                    case 'repairOrder':
                    {
                        $responseTemp = $entity->getRepairOrder()->getTicketNumber();
                        break;
                    }

                    case 'created_at':
                        {
                            $responseTemp = $entity->getCreatedAt()->format('Y-m-d H:i:s');
                            break;
                        }

                    case 'logTime':
                    {

                        $minutes = $entity->getLogTimeMinutes();

                        if($entity->getAction() == "INGRESO"){
                            $objAdminSetting = $em->getRepository('SolucelAdminBundle:AdminSetting')->find(1);
                            $entryEstimatedTime = intval($objAdminSetting->getEntryEstimatedTime());

                            if($minutes > $entryEstimatedTime){
                                $responseTemp = "<label style='background-color: #F56748;width: 100%;font-weight: bold;color:#000;' >$minutes</label>";
                            }
                            else{
                                $responseTemp = $minutes;
                            }

                        }
                        else{
                            $responseTemp = $minutes;
                        }


                        break;
                    }

                }

                // Add the found data to the json
                $response .= $this->get("services")->escapeJsonString($responseTemp);

                if(++$j !== $nbColumn)
                    $response .='","';
            }

            $response .= '"]';

            // Not on the last item
            if(++$i !== $selected_objects_count)
                $response .= ',';
        }

        $response .= ']}';

        // Send all this stuff back to DataTables
        $returnResponse = new JsonResponse();
        $response =  json_decode($response);
        $returnResponse->setData($response);

        return $returnResponse;

    }



}


?>
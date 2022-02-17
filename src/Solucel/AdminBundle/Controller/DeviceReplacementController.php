<?php

namespace Solucel\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Session;


use Solucel\AdminBundle\Entity\DeviceReplacement;
use Solucel\AdminBundle\Form\DeviceReplacementType;
use Solucel\AdminBundle\Entity\DeviceReplacementType as ReplacementType;
use Solucel\AdminBundle\Entity\DeviceBrand;
use Solucel\AdminBundle\Entity\DeviceModel;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * DeviceReplacement controller.
 *
 */
//todo: new ajax list
class DeviceReplacementController extends Controller
{


    protected $em;

    protected $repository;
    private  $renderer;
    private $userLogged;
    private $role;
    private $session;


    // Set up all necessary variable
    protected function initialise()
    {
        $this->session = new Session();
        $this->em = $this->getDoctrine()->getManager();
        $this->repository = $this->em->getRepository('SolucelAdminBundle:DeviceReplacement');

        $this->renderer = $this->get('templating');
        $this->userLogged = $this->session->get('userLogged');
        $this->role = $this->session->get('userLogged')->getRole()->getName();

    }

	
    /**
     * Lists all DeviceReplacement entities.
     *
     */
    public function indexAction()
    {

    	$this->get("services")->setVars('deviceReplacement');
        $this->initialise();

        return $this->render('SolucelAdminBundle:DeviceReplacement:index.html.twig', array(
            'role' => $this->role
        ));		
    }


    public function listDatatablesAction(Request $request)
    {

        $this->get("services")->setVars('deviceReplacement');

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

        }
        else // If the request is not a POST one, die hard
            die;

        // Process Parameters
        $myArray = array();
        $arrTMP = array();
        //var_dump($myArray);die;

        $results = $this->repository->getRequiredDTData($start, $length, $orders, $search, $columns);
        $objects = $results["results"];
        $selected_objects_count = count($objects);
        //var_dump($selected_objects_count);die;

        $i = 0;
        $response = "";

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
                    case 'name':
                    {
                        $responseTemp = $entity->getName();
                        break;
                    }

                    case 'code':
                    {
                        $responseTemp = $entity->getReplacementCode();
                        break;
                    }

                    case 'brand':
                    {
                        $responseTemp = $entity->getDeviceBrand()->getName();
                        break;
                    }
                    case 'model':
                    {
                        $responseTemp = $entity->getDeviceModel()->getName();
                        break;
                    }


                    case 'actions':
                    {

                        $urlEdit = $this->generateUrl('solucel_admin_devicereplacement_edit', array('id' => $entity->getId()));

                        $edit = "<a href='".$urlEdit."'><div class='btn btn-sm btn-primary'><span class='fa fa-search'></span></div></a>&nbsp;&nbsp;";

                        $urlDelete = $this->generateUrl('solucel_admin_devicereplacement_delete', array('id' => $entity->getId()));
                        $delete = "<a class='btn btn-danger btn-delete'  href='".$urlDelete."'><span class='fa fa-trash-o'></span></a>";

                        $responseTemp = $edit.$delete;
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
        $myItems = $response;
        //($request, $repository, $results, $myItems){
        $return = $this->get("services")->serviceDataTable($request, $this->repository, $results, $myItems, $selected_objects_count);

        return $return;

    }



    /**
     * Creates a new DeviceReplacement entity.
     *
     */
    public function newAction(Request $request)
    {
    	
		$this->get("services")->setVars('deviceReplacement');
        $entity = new DeviceReplacement();
        $form   = $this->createCreateForm($entity);
		 
	
        return $this->render('SolucelAdminBundle:DeviceReplacement:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

 

    /**
     * Finds and displays a DeviceReplacement entity.
     *
     */
    public function showAction($entity)
    {
        $deleteForm = $this->createDeleteForm($entity);

        return $this->render('solucel_admin_devicereplacement/show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing DeviceReplacement entity.
     *
     */
    public function editAction(Request $request, $id)
    {
    	$this->get("services")->setVars('deviceReplacement');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:DeviceReplacement')->find($id);
				
        $deleteForm = $this->createDeleteForm($entity);
		$editForm = $this->createEditForm($entity);
        
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirectToRoute('solucel_admin_devicereplacement_edit', array('id' => $id));
        }

        return $this->render('SolucelAdminBundle:DeviceReplacement:edit.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }


    public function deleteAction(Request $request, $id)
    {

        $this->get("services")->setVars('deviceReplacement');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:DeviceReplacement')->find($id);
        $form = $this->createDeleteForm($entity);
        $form->handleRequest($request);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Replacement entity.');
        }
        else{

            //SOFT DELETE
            $entity->setEnabled(0);
            //$customHelper->blameOnMe($entity);
            $this->em->persist($entity);
            $this->em->flush();

        }

        $this->get('services')->flashSuccess($request);
        return $this->redirectToRoute('solucel_admin_devicereplacement_index');

    }
	


    /**
     * Creates a form to delete a DeviceReplacement entity.
     *
     * @param DeviceReplacement The DeviceReplacement entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('solucel_admin_devicereplacement_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

	
    /**
     * Creates a new DeviceReplacement entity.
     *
     */
    public function createAction(Request $request)
    {
		
		$this->get("services")->setVars('deviceReplacement');
        $entity = new DeviceReplacement();
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
            return $this->redirect($this->generateUrl('solucel_admin_devicereplacement_index'));
			 
        }
		/*
		else{
			print "FORMULARIO NO VALIDO";DIE;
		}*/

        return $this->render('SolucelAdminBundle:DeviceReplacement:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a DeviceReplacement entity.
     *
     * @param DeviceReplacement $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm($entity)
    {
    	//$this->setVars();
        $form = $this->createForm(DeviceReplacementType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_devicereplacement_create'),
            'method' => 'POST'
            //'client' => $this->userLogged,
        ));



        return $form;
    }	
	

	
	
    /**
    * Creates a form to edit a DeviceReplacement entity.
    *
    * @param DeviceReplacement $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm($entity)
    {
    	
    	//$this->setVars();
        $form = $this->createForm(DeviceReplacementType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_devicereplacement_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            //'client' => $this->userLogged,
        ));
		//print "entra";die;


        return $form;
    }
	
	
    /**
     * Edits an existing DeviceReplacement entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
    	$this->get("services")->setVars('deviceReplacement');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:DeviceReplacement')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DeviceReplacement entity.');
        }

        $deleteForm = $this->createDeleteForm($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

			$this->get('services')->flashSuccess($request);
            return $this->redirect($this->generateUrl('solucel_admin_devicereplacement_index', array('id' => $id)));
			 
        }

        return $this->render('SolucelAdminBundle:DeviceReplacement:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }	


    public function loadAction(Request $request)
    {
    	
		/*EL FORMATO DEL ARCHIVO DEBE DE SER MS-DOS CSV*/
		/*MAC LOS FORMATEA MAL*/
    	$this->get("services")->setVars('deviceReplacement');
        $em = $this->getDoctrine()->getManager();

        if(isset($_REQUEST["submit"])){
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
			
			 foreach ($line_of_text as $key => $value) {
			 	
				/*
				print "<pre>";
				var_dump($value);die;
				 * */
				
				
				if($key != 0){
					//**buscarlo en base de datos y sustituir con ID

                    //0 - codigo// **
					//1 - nombre
					//2 - marca// **
					//3 - modelo// **
					//4 - tipo de repuesto
					//5 - base de datos

                    //x - tipo -> general = 1// **
						
					$name = $this->get("services")->cleanString(utf8_encode(trim($value[1])));
					
					$objReplacement = new DeviceReplacement();
					$objReplacement->setName($name);
					
					$replacementCode = $this->get("services")->cleanString(utf8_encode(trim($value[0])));
					$objReplacement->setReplacementCode($replacementCode);
					
					//Mano de obra
					/*
					$type = $this->findEntity('Solucel\AdminBundle\Entity\DeviceReplacementType', "Reparacion");
					$type = $type[0];
					$objReplacement->setDeviceReplacementType($type);
					 * */
					
					$repType = trim($value[4]);
					if($repType == ""){
						$replacementType = "General";
					} 
					else{
						$replacementType = $this->get("services")->cleanString(utf8_encode(trim($repType)));	
					}
					
					//var_dump($replacementType);die;
					
					//$entity = $this->findEntity('Solucel\AdminBundle\Entity\DeviceReplacementType', $replacementType);
					$entity = $em->getRepository('SolucelAdminBundle:DeviceReplacementType')->findByName($replacementType);

					if(!empty($entity)){
					    //print "1";

						$entity = $entity[0];
                        //var_dump($entity->getId());
					} 
					else{
                        //print "2";die;
						$entity = new ReplacementType();
						$entity->setName($replacementType);
			            $em->persist($entity);
			            //$em->flush();
						
					} 	
					$objDeviceReplacementType = $entity;
					
					
					//**BRAND**//
					$field =  trim($value[2]);
					
					//$entity = $this->findEntity('Solucel\AdminBundle\Entity\DeviceBrand', $field);
                    $entity = $em->getRepository('SolucelAdminBundle:DeviceBrand')->findByName($field);
					if(!empty($entity)){
						$entity = $entity[0];
					} 
					else{
						
						$entity = new DeviceBrand();
						$entity->setName($field);
						$entity->setEnabled(1);
			            $em->persist($entity);
			            //$em->flush();
					} 	
					$objBrand = $entity;
					
					
					//**MODEL**//
					$field =  trim($value[3]);
					
					//$entity = $this->findEntity('Solucel\AdminBundle\Entity\DeviceModel', $field);
                    $entity = $em->getRepository('SolucelAdminBundle:DeviceModel')->findBy(array("name" => $field, "enabled" => 1));
					if(!empty($entity)){
						$entity = $entity[0];
					} 
					else{
						
						$entity = new DeviceModel();
						$entity->setName($field);
						//$entity->setStyle($field);
						$entity->setDeviceBrand($objBrand);
						$entity->setIsObsolete(0);
                        $entity->setEnabled(1);
						
			            $em->persist($entity);
			            //$em->flush();
					} 
					$objModel = $entity;
					
					
					//**DATABASE**//
					$database =  trim(strtoupper($value[5]));
					
					//replacement complement
					
					$objReplacement->setDeviceBrand($objBrand);
					$objReplacement->setDeviceModel($objModel);
					$objReplacement->setStrdatabase($database);
					$objReplacement->setDeviceReplacementType($objDeviceReplacementType);
					$objReplacement->setDescription("");
                    $objReplacement->setEnabled(1);
    				$em->persist($objReplacement);

				 }
			}
			$em->flush();
			
			$this->get('services')->flashSuccess($request);
		    return $this->redirectToRoute('solucel_admin_devicereplacement_index');			        	
        }


		//return $this->redirect($this->generateUrl('solucel_admin_devicereplacement_load'));
		return $this->render('SolucelAdminBundle:DeviceReplacement:load.html.twig', array());
    }



	public function findEntity($entity, $searchTerm){

		$em = $this->getDoctrine()->getManager();
		$qb = $em->createQueryBuilder();		
		$obj = $qb->select('x')->from($entity, 'x')
				->where( $qb->expr()->like('x.name', $qb->expr()->literal('%' . trim($searchTerm). '%')) );

                if( ($entity == 'Solucel\AdminBundle\Entity\DeviceBrand') || ($entity == "Solucel\AdminBundle\Entity\DeviceModel") ){
                    $qb->andWhere('x.enabled = 1');
                }
				$qb->getQuery()
				//->getArrayResult();
				//->execute();
                //->getSingleResult();
                //->toIterable();
                ->getScalarResult();


        var_dump($obj);die;
		return $obj;
		
	}
	


}

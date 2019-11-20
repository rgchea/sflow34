<?php

namespace Solucel\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Session;


use Solucel\AdminBundle\Entity\RepairOrderFix;
use Solucel\AdminBundle\Form\RepairOrderFixType;

use Solucel\AdminBundle\Entity\RepairOrder;
use Solucel\AdminBundle\Entity\RepairOrderStatus;
use Solucel\AdminBundle\Entity\RepairOrderDeviceFixType;
use Solucel\AdminBundle\Entity\RepairOrderDeviceReplacement;
use Solucel\AdminBundle\Entity\RepairOrderQualityControl;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * RepairOrderFix controller.
 *
 */
class RepairOrderFixController extends Controller
{
    /**
     * Lists all RepairOrderFix entities.
     *
     */
    public function indexAction()
    {

		//phpinfo();die;
 		//print "die";die;
		$this->get("services")->setVars('repairOrderFix');
    	$session = new Session();

        $em = $this->getDoctrine()->getManager();

		$user = $session->get('user_logged');
		$entities = $em->getRepository('SolucelAdminBundle:RepairOrder')->getTecAssignedOrders($user->getId());

		/*
		print "<pre>";
		var_dump($entities);die;
		 * */

        //$entities = $em->getRepository('SolucelAdminBundle:RepairOrder')->getOrders();

        return $this->render('SolucelAdminBundle:RepairOrderFix:index.html.twig', array(
            'entities'       => $entities,

        ));
    }

    /**
     * Creates a new RepairOrderFix entity.
     *
     */
    public function newAction(Request $request)
    {
    	$this->get("services")->setVars('repairOrderFix');
        $entity = new RepairOrderFix();
        $form   = $this->createCreateForm($entity);


        return $this->render('SolucelAdminBundle:RepairOrderFix:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }


    /**
     * Finds and displays a RepairOrderFix entity.
     *
     */
    //public function deleteAction(Request $request, $id)
    public function showAction(Request $request, $id)
    {

		$this->get("services")->setVars('repairOrderFix');
    	$session = new Session();
		$em = $this->getDoctrine()->getManager();
		$user = $session->get('user_logged');


		$objOrder =  $em->getRepository('SolucelAdminBundle:RepairOrder')->find($id);

		//chequear garantia
		$entity = $em->getRepository('SolucelAdminBundle:RepairOrderFix')->findByRepairOrder($id);
		$entity = $entity[0];
		//var_dump($entity);die;

		$fixes = $em->getRepository('SolucelAdminBundle:RepairOrderDeviceFixType')->findByRepairOrder($id);
		/*
		print "<pre>";
		var_dump($fixes);die;
		 * */
		$replacements = $em->getRepository('SolucelAdminBundle:RepairOrderDeviceReplacement')->findByRepairOrder($id);


        //$orderStatus = $objOrder->getRepairStatus()->getName();
        $orderStatus = $em->getRepository('SolucelAdminBundle:RepairOrder')->getOrderStatus($id);

        $deleteForm = $this->createDeleteForm($entity);
		$editForm = $this->createEditForm($entity);

        $editForm->handleRequest($request);

		//print "<pre>";
		//var_dump($arrRegisters);die;
		//$deviceBrand = $em->getRepository('SolucelAdminBundle:DeviceBrand')->findAll();

        return $this->render('SolucelAdminBundle:RepairOrderFix:show.html.twig', array(
            'entity' => $entity,
            'fixes' => $fixes,
            'replacements' => $replacements,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'order_status' => $orderStatus["name"],
            'orderID' => $id,
            //'deviceBrand' => $deviceBrand

        ));

    }


	public function checkWarrantyAction(Request $request){

		$em = $this->getDoctrine()->getManager();
		$result = $em->getRepository('SolucelAdminBundle:RepairOrder')->checkWarranty($_REQUEST["orderID"]);

		print $result;
		die;

	}

	public function orderSAPAction(Request $request){
		

    	$session = new Session();
        $em = $this->getDoctrine()->getManager();

		$user = $session->get('user_logged');

		/*
		print "<pre>";
		var_dump($_REQUEST);die;
		 * */

		$orderID =  $_REQUEST["orderID"];
		$orderFixID = $_REQUEST["entityID"];
		$disccount = $_REQUEST["orderID"];


		$replacements = $em->getRepository('SolucelAdminBundle:RepairOrderDeviceReplacement')->findByRepairOrder($orderID);
		$objOrder = $em->getRepository('SolucelAdminBundle:RepairOrder')->find($orderID);
		//$objOrder = $objOrder[0];

		$objOrderFix = $em->getRepository('SolucelAdminBundle:RepairOrderFix')->findByRepairOrder($orderID);
		if($objOrderFix != NULL){
			$objOrderFix = $objOrderFix[0];
		}

		$clientCode = $objOrder->getClient()->getClientCode();
		///////total del doc
		$totalDoc = 0;


		///SAP WS

		 //PROD
		//http://143.208.180.250:21489/pedidos/RepInventory.asmx

		//DEV
		//http://143.208.180.250:21489/webserviceinventoryTest/RepInventory.asmx

		$client = new \nusoap_client("http://143.208.180.250:21489/pedidos/RepInventory.asmx?wsdl", "wsdl");
		$client->setEndPoint("http://143.208.180.250:21489/pedidos/RepInventory.asmx?wsdl");



		//var_dump($client);die;
		//$client->se
		$result = 1;

		$err = $client->getError();
		if ($err) {
			/*
			print 'Error en Constructor' . $err ;*/
			$result = 0;
		}


		if ($client->fault) {

			/*echo 'Fallo';
			die;*/
			$result = 0;

		} else {	// Chequea errores
			$err = $client->getError();
			if ($err) {		// Muestra el error
				/*
					echo 'Error ' . $err ;
					die;
				 * */

				 $result = 0;

			} else {		// Muestra el resultado

				//print "entra nitido"; die;
				//echo 'Resultado';
				//print_r ($response);

				//print "entra aca no hay error en el constructor";die;

				$now = date("Y-m-d") . 'T' . date("H:i:s");
				//print $now;die;

				foreach ($replacements as $replacement) {
					//Calcular el total del documento
					//$totalDoc += intval($value["quantity"]) * (floatval($value["price"]) + floatval($value["cost"]) );
					$totalDoc += intval($replacement->getQuantity()) * (floatval($replacement->getPrice()) );
				}
				$totalDoc = $totalDoc;


				///UPDATE FIXING PRICE
				$objOrderFix->setFixingPrice($totalDoc);

				$arrTMP = array();

				$return = array();
				$return["request"] = array();



				foreach ($replacements as $replacement) {

					//var_dump($replacement->getId());die;


					//print $value["cost"];
					$DetalleOrden = array();
					$DetalleOrden['cSapUser'] = 'cargas_solucel';
					$DetalleOrden['cSapPassword'] = '1111';
					$DetalleOrden['cDate'] = $now;
					$DetalleOrden['cPayGroup'] = -1;

					//base de datos a consultar
					$db = $replacement->getDeviceReplacement()->getStrdatabase();

					//cSeries al enviar pedidos a SeedStock debe enviarse con el valor 9 mientras que para Solucel es 6.
					$cSeries = $db == "SOLUCEL" ? 6 : 9;
					$DetalleOrden['cSeries'] = 6;

					///codBodega
					$DetalleOrden['cTipoMoneda'] = 'QTZ';
					$DetalleOrden['cCardCode'] = $clientCode;
					$DetalleOrden['cTotalDoc'] = floatval($totalDoc);

					$objReplacement = $em->getRepository('SolucelAdminBundle:DeviceReplacement')->find($replacement->getDeviceReplacement()->getId());
					$DetalleOrden['cCodRepuesto'] = $objReplacement->getReplacementCode();
					$DetalleOrden['cCantidad'] = $replacement->getQuantity();
					//$DetalleOrden['cDescripcion'] = $objReplacement->getName();
					//$linePrice = intval($value["quantity"]) * (floatval($value["price"]) + floatval($value["cost"]) );
					$linePrice = intval($replacement->getQuantity()) * (floatval($replacement->getPrice()) );
					$DetalleOrden['cLinePriceAfterVat'] = floatval(number_format($linePrice, 2)) ;
					$DetalleOrden['cBodega'] = $objOrderFix->getAssignedTo()->getServiceCenter()->getStorehouse()->getStorehouseCode();
					$DetalleOrden['cDiscountPercent'] = 0.0;
					$DetalleOrden['cBoleta'] =  strval($objOrder->getId())."";
					$DetalleOrden['cBD'] =  $db;

					$DetalleOrden['cResultado'] = "";

					$arrTMP[] = $DetalleOrden;

					//$return["request"][] = $DetalleOrden;
					/*
					print "<pre>";
					var_dump($arrTMP);die;
					 * */

				}

				$return["request"] = $arrTMP;

				 $response = $client->call('Order', array('DetalleOrdenList' => array("DetalleOrden" => $arrTMP)));
				 //var_dump($response);die;
				 //chea change

				 if(!empty($response)){
				 	if(isset($response["OrderResult"])){
				 		if($response["OrderResult"]["Resultado"] == "1"){//:Se agrego con Exito
				 			$result = 1;
				 		}
						else{
							$result = 0;
						}
				 	}
					else{
						$result = 0;
					}
				 }
				 else{
				 	$result = 0;
				 }

				 /*si response =
				  * array(1) {
					  ["OrderResult"]=>
					  array(1) {
					    ["Resultado"]=>
					    string(21) "1:Se agrego con Exito"
					  }
					}
				  *MOSTRAR MENSAJE QUE SE HIZO LA ORDEN DE COMPRA RESPECTIVA
				  * */

				  //ELSE: EXISTIO UN PROBLEMA Y NO SE PUDO REALIZAR LA ORDEN DE COMPRA RESPECTIVA

				  //FIXING_PRICE?????
				  //TOTAL PRICE
				  ///AGREGAR CAMPO DE DESCUENTO, EN QUE PARTE DEL FLUJO?

				 //var_dump($response);

			}
		}

		/*
       	public string cSapUser { Usuario de SAP}
        public string cSapPassword { Password de SAP }
        public DateTime cDate { FEcha de la Orden }
        public int cPayGroup { Codigo de Grupo de pago Ejemplo 4 }
        public int cSeries { Codigo de la Serie Ejemplo 6 }
        public string cTipoMoneda { QTZ }
        public string cCardCode { Codigo del Cliente }
        public double cTotalDoc { Total del Documento }
        public string cCodRepuesto { Codigo del producto }
        public string cDescripcion { Descripcion del Producto }
        public double cCantidad { Cantidad en Unidades}
        public double cLinePriceAfterVat { Total por linea con Iva }
        public double cDiscountPercent { Descuento por linea }
        public string cResultado { Este es de control interno }
		 * */


		 //var_dump($now);die;

		if($result){
			//actualizar client_repairment_confirmation
			$objOrderFix = $em->getRepository('SolucelAdminBundle:RepairOrderFix')->find($orderFixID);
			$objOrderFix->setOrderConfirmation(1);
			$objOrderFix->setUpdatedAtValue();
			$em->persist($objOrderFix);
			
			$objOrder->setUpdatedAtValue();
			$em->persist($objOrder);
			
			$em->flush();

			//repair_log -> sap order

		}

		/*
		$return["availability"] = $availability;
		return new JsonResponse($return);
		 * */
		return new JsonResponse($result);

	}




	public function requestSAPAction(Request $request){

    	$session = new Session();
		$em = $this->getDoctrine()->getManager();
		$user = $session->get('user_logged');

		//var_dump($_REQUEST);DIE;
		//$checkRepairment = intval($_REQUEST["checkRepairment"]);

		$arrReplacements = $_REQUEST["arrValues"];

		//WSDL CLIENT

		//PROD
		//http://143.208.180.250:21489/pedidos/RepInventory.asmx

		//DEV
		//http://143.208.180.250:21489/webserviceinventoryTest/RepInventory.asmx

		//$client = new \nusoap_client("http://143.208.180.250:21489/pedidos/RepInventory.asmx?wsdl", "wsdl");
		//$client->setEndPoint("http://143.208.180.250:21489/pedidos/RepInventory.asmx?wsdl");


		//var_dump($client);die;

		/*
		$err = $client->getError();
		if ($err) {
			print 'Error en Constructor' . $err ;
			return false;
			die;
		}*/

		$orderID = $request->get("orderID");
		$objOrder = $em->getRepository('SolucelAdminBundle:RepairOrder')->find($orderID);
		$orderTicketNumber = $objOrder->getTicketNumber();
		$entityID = intval($_REQUEST["entityID"]);
		$objOrderFix = $em->getRepository('SolucelAdminBundle:RepairOrderFix')->find($entityID);
		$clientConfirmation = intval($objOrderFix->getClientRepairmentConfirmation());
		//var_dump($clientConfirmation);die;

		$clientCode = $objOrder->getClient()->getClientCode();
		$storehouseCode = $objOrder->getServiceCenter()->getStorehouse()->getStorehouseCode();
		//$storehouseCode = "01";
		//var_dump($clientCode);die;

		$requestResponse = array();
		$availability = array();
		//$availability["total"] = 0;

		$replacementAvailability = 1;//este es la disponibilidad en general de los repuestos
		$replacementsTotalAmount = 0;


		$warranty = intval($objOrderFix->getHasWarranty());


		/*
		if($warranty){


			$objOrderFix->setClientRepairmentConfirmation(1);
			$em->persist($objOrderFix);
			$em->flush();
		}
		else{


			$objOrderFix->setClientRepairmentConfirmation(0);
			$em->persist($objOrderFix);
			$em->flush();

		}
		 * */


		$return = array();
		$return["request"] = array();
		
		$boolAvailable = 1;
		
		foreach ($arrReplacements as $replacement => $arr) {
			
			$key = $replacement;//indice de contador
			$replacementID = intval($arr["id"]);
			$objReplacement = $em->getRepository('SolucelAdminBundle:DeviceReplacement')->find($replacementID);
			$replacementCode = $objReplacement->getReplacementCode();
			$strdatabase =  $objReplacement->getStrdatabase();
			$db = $strdatabase == "" ? "SOLUCEL": $strdatabase;
			
			$quantity = intval($arr["quantity"]);
			
					$availability[$key]["availability"]	= 1;
					$availability[$key]["price"] = floatval(0.00);
					//$availability[$key]["cost"] = floatval($result["Costo"]);
					//$availability[$key]["total"] = $quantity * floatval($result["Precio"] + $result["Costo"]);			
			
					$objOrderReplacement = $em->getRepository('SolucelAdminBundle:RepairOrderDeviceReplacement')->findBy(array("repairOrder"=> $orderID, "deviceReplacement" => $replacementID));
					$objOrderReplacement = $objOrderReplacement[0];


					//var_dump($objOrderReplacement);die;
					$objOrderReplacement->setPrice(floatval(0.00));
					$objOrderReplacement->setCost(0);
					$objOrderReplacement->setAvailable($boolAvailable);

					$em->persist($objOrderReplacement);
					$em->flush();
			
			
		}

		/*	

		foreach ($arrReplacements as $replacement => $arr) {

			//var_dump($arr);die;
			//$key = $arr["id"];
			$key = $replacement;//indice de contador
			$replacementID = intval($arr["id"]);
			$objReplacement = $em->getRepository('SolucelAdminBundle:DeviceReplacement')->find($replacementID);
			$replacementCode = $objReplacement->getReplacementCode();
			$strdatabase =  $objReplacement->getStrdatabase();
			$db = $strdatabase == "" ? "SOLUCEL": $strdatabase;



			$quantity = intval($arr["quantity"]);

			/*
			print "<pre>";
			var_dump("CodCliente:".$clientCode);
			var_dump("CodRepuesto:".$replacementCode);
			var_dump("CodBodega:".$storehouseCode);
			var_dump("Cantidad:".$quantity);
			var_dump("Confirmado:".$quantity);
			 * */

			//nusoap_client ($endpoint, $wsdl=false, $proxyhost=false, $proxyport=false, $proxyusername=false, $proxypassword=false, $timeout=0, $response_timeout=30, $portName= '')
 			//call ($operation, $params=array(), $namespace='http://tempuri.org', $soapAction='', $headers=false, $rpcParams=null, $style='rpc', $use='encoded')

			/*
			$requestArray = array('CodRepuesto'=>$replacementCode,
									'CodBodega'=>$storehouseCode,
									'CodCliente'=>$clientCode,
									'Cantidad'=>$quantity,
									'Confirmado'=>'0',
									'bd'=>$db,
									);

			$return["request"][] = $requestArray;
			//var_dump($requestArray);

			$response = $client->call('GetInventoryXml', $requestArray);
									//http://localhost/GetInventory/", "http://localhost/GetInventory/GetInventoryXml");

			//var_dump($response);die;

			if ($client->fault) {
				
				//echo 'Fallo';
				//print_r($response);
				return new JsonResponse(array("0"=>false));
			} else {	// Chequea errores
				$err = $client->getError();
				if ($err) {		// Muestra el error
				
					//echo 'Error ' . $err ;
					//return false;
				 
					return new JsonResponse(array("0"=>false));
				} else {		// Muestra el resultado

					
					//echo 'Resultado';
					//print_r ($response);
					 
					//return $response["GetInventoryXmlResult"];
					//$requestResponse[$replacement] = $response["GetInventoryXmlResult"];
					//var_dump($response["GetInventoryXmlResult"]);die;
					$result = $response["GetInventoryXmlResult"];
					$boolAvailable = 0;//este es por la disponibilidad individual de cada repuesto
					if($result["Cantidad"] >= $quantity){
						$boolAvailable = 1;
					}
					else{
						$replacementAvailability = 0;
            			$clientMail = trim($objOrder->getClient()->getEmail());

						if($warranty){
							//SEND EMAIL ALERT TO contadorgeneral@gruposolucel.com, compras@gruposolucel.com
							if( intval($objOrderFix->getReplacementEmail()) == 0){//revisar que no se haya enviado correo anteriormente
								$this->sendReplacementEmail($orderTicketNumber, $replacementCode, $quantity, $storehouseCode, $entityID);
                				$this->sendPendingStatusMailClient($objOrder->getId, $orderTicketNumber, $clientMail, $objOrder->getCreatedAt()->format("Y-m-d H:i:s"));

							}
						}
						else{
							if($clientConfirmation == 1){
								$this->sendReplacementEmail($orderTicketNumber, $replacementCode, $quantity, $storehouseCode, $entityID);
                				$this->sendPendingStatusMailClient($objOrder->getId, $orderTicketNumber, $clientMail, $objOrder->getCreatedAt()->format("Y-m-d H:i:s"));
							}

						}
						//clientRepairmentConfirmation


					}

					$availability[$key]["availability"]	= $boolAvailable;
					$availability[$key]["price"] = floatval($result["Precio"]);
					//$availability[$key]["cost"] = floatval($result["Costo"]);
					//$availability[$key]["total"] = $quantity * floatval($result["Precio"] + $result["Costo"]);

					$replacementsTotalAmount += $quantity * floatval($result["Precio"]);

					//$availability["total"] += $availability[$replacement]["total"];
					///ACA GUARDAR EN BASE DE DATOS POR CADA UNO DE LOS REPUESTOS
					$objOrderReplacement = $em->getRepository('SolucelAdminBundle:RepairOrderDeviceReplacement')->findBy(array("repairOrder"=> $orderID, "deviceReplacement" => $replacementID));
					$objOrderReplacement = $objOrderReplacement[0];


					//var_dump($objOrderReplacement);die;
					$objOrderReplacement->setPrice(floatval($result["Precio"]));
					$objOrderReplacement->setCost(0);
					$objOrderReplacement->setAvailable($boolAvailable);

					$em->persist($objOrderReplacement);
					$em->flush();
				}
			}
		}
		//end foreach
		*/


		///ACA CONSULTAR LA BASE DE DATOS INTERNA PARA EL STOCK


		//var_dump($warranty);die;

		if($warranty){

			$objOrderFix->setClientRepairmentRequest(1);
			$objOrderFix->setClientRepairmentConfirmation(1);
			$objOrderFix->setUpdatedAtValue();
			$em->persist($objOrderFix);
			$em->flush();

			if(!$replacementAvailability){//no hay algún repuesto en existencia
				$newStatus = "PENDIENTE POR REPUESTO";

			}
			else{
				$newStatus = "EN REPARACION";
			}

		}
		else{
			//PENDIENTE CONFIRMACION DE COSTO?


			if($clientConfirmation == 0){
				//enviar correo al cliente para confirmacion de la orden
				if(intval($objOrderFix->getReplacementEmail()) == 0){//no se ha enviado exitosamente correo de confirmacion a cliente
					$this->sendClientconfirmationEmail($replacementsTotalAmount, $orderTicketNumber, $entityID);
				}

				$newStatus = "PENDIENTE CONFIRMACION DE COSTO";
				$objOrderFix->setClientRepairmentRequest(0);
				$objOrderFix->setClientRepairmentConfirmation(0);

			}
			else{
				$newStatus = "EN REPARACION";
				$objOrderFix->setClientRepairmentRequest(1);
				$objOrderFix->setClientRepairmentConfirmation(1);

			}

			$em->persist($objOrderFix);
			$em->flush();


		}



		$status = $em->getRepository('SolucelAdminBundle:RepairStatus')->findByName($newStatus);
		$status = $status[0];

		$orderStatus = $objOrder->getRepairStatus()->getName();
		//$orderStatus = $em->getRepository('SolucelAdminBundle:RepairOrder')->getOrderStatus($id);

		if($orderStatus != $newStatus){
			//update status

			$objOrder->setRepairStatus($status);
			$objOrder->setUpdatedAtValue();
			$em->persist($objOrder);
			$em->flush();

			$objRepairOrderStatus = new RepairOrderStatus();
			$objRepairOrderStatus->setCreatedAtValue();
			$objRepairOrderStatus->setRepairStatus($status);
			$objRepairOrderStatus->setRepairOrder($objOrder);

			$user = $em->getRepository('SolucelAdminBundle:User')->find($user->getId());
			$objRepairOrderStatus->setCreatedBy($user);

			$em->persist($objRepairOrderStatus);
			$em->flush();

		}


		/*
		print "<pre>";
		var_dump($availability);die;*/
		//$availability["total"] = $replacementsTotalAmount;
		
		

		$return["availability"] = $availability;
		return new JsonResponse($return);

	}

	public function sendReplacementEmail($orderID, $replacementCode, $quantity, $storehouseCode, $entityID){

    $session = new Session();
		$em = $this->getDoctrine()->getManager();
		$user = $session->get('user_logged');


		$objOrderFix = $em->getRepository('SolucelAdminBundle:RepairOrderFix')->find($entityID);

		$subject = "Alerta de Repuestos, orden No.".$orderID;
		//$to = "contadorgeneral@gruposolucel.com";
		$to = array("contadorgeneral@gruposolucel.com", "compras@gruposolucel.com");

		$body = "Aviso importante, se requieren al menos ".$quantity." repuestos con codigo ".$replacementCode." en la bodega con codigo ".$storehouseCode." URGENTEMENTE.";
		$send = $this->get("services")->generalTemplateMail($subject, $to, $body, $from = null);

		if($send == 1){
		//if(0){
			$objOrderFix->setReplacementEmail(1);
			$em->persist($objOrderFix);
			$em->flush();
		}
		//return $send;

	}


  public function sendPendingStatusMailClient($orderID, $ticket, $clientMail, $createdAt){
    //sendPendingStatusMailClient($orderTicketNumber, $clientMail, $objOrder->getCreatedAt()->format("Y-m-d H:i:s"));

    $session = new Session();
		$em = $this->getDoctrine()->getManager();
		$user = $session->get('user_logged');


		$to = array($clientMail, "sertec@gruposolucel.com");

		$body = "Estimado cliente le informamos que su unidad se encuentra pendiente por repuesto. Orden: {$ticket}, ingresada {$createdAt}. Le notificaremos por esta vía cuando esté lista para la entrega.";
		$send = $this->get("services")->generalTemplateMail("Pendiente #{$ticket}", $to, $body, $from = null);

		if($send == 1){
		//if(0){
			$objOrderFix = $em->getRepository('SolucelAdminBundle:RepairOrderFix')->findOneByRepairOrder($orderID);
			$objOrderFix->setReplacementEmail(1);
			$objOrderFix->setUpdatedAtValue();
			$em->persist($objOrderFix);
			$em->flush();
		}

  }


	public function sendClientconfirmationEmail($total, $orderTicket, $orderFixID){
		//print "entra correo para cliente";die;

    	$session = new Session();
		$em = $this->getDoctrine()->getManager();
		$user = $session->get('user_logged');


		$objOrder = $em->getRepository('SolucelAdminBundle:RepairOrder')->findByTicketNumber($orderTicket);
		if($objOrder){
			$objOrder = $objOrder[0];
		}
		$objOrderFix = $em->getRepository('SolucelAdminBundle:RepairOrderFix')->find($orderFixID);

		$subject = "Confirmación de Reparación";
		//$to = "contadorgeneral@gruposolucel.com";

		$to = trim($objOrder->getClient()->getEmail());
		$newline = "\n";
		$body = "Buen día estimado usuario, se solicita autorizar la reparación del dispositivo:$newline";
		$body .= $objOrder->getDeviceType()->getName().$newline;
		$body .= $objOrder->getDeviceBrand()->getName().$newline;
		$body .= $objOrder->getDeviceModel()->getName().$newline;
		$body .= $objOrder->getDeviceColor()->getName().$newline.$newline;
		$body .= ". Con un costo estimado de Q.".number_format($total, 2).$newline;
		$body .= ". Por favor, tener en cuenta que el costo puede variar en un rango pequeño. Responder este correo para proceder o cancelar la reparación del dispositivo mencionado.";

		$send = $this->get("services")->generalTemplateMail($subject, $to, $body, $from = null);

		if($send == 1){
		//if(0){
			$objOrderFix->setClientRepairmentRequest(1);
			$objOrderFix->setClientRepairmentConfirmation(0);
			$objOrderFix->setUpdatedAtValue();
			$em->persist($objOrderFix);
			$em->flush();

		}

		//return $send;
	}

	public function checkReplacementEmailAction(Request $request){

		$session = new Session();
		$em = $this->getDoctrine()->getManager();
		$user = $session->get('user_logged');


		$objOrderFix =  $em->getRepository('SolucelAdminBundle:RepairOrderFix')->find(intval($_REQUEST["id"]));
		//var_dump($objOrderFix->getReplacementEmail());die;
		//print intval($objOrderFix->getReplacementEmail());

		return  new JsonResponse($objOrderFix->getReplacementEmail());
	}

    /**
     * Displays a form to edit an existing RepairOrderFix entity.
     *
     */
    public function editAction(Request $request, $id)
    {

		/*
		$response = $this->requestSAPAction(array());
		var_dump($response);
		die;
		 * */


		$this->get("services")->setVars('repairOrderFix');
    	$session = new Session();
		$em = $this->getDoctrine()->getManager();
		$user = $session->get('user_logged');


		$objOrder =  $em->getRepository('SolucelAdminBundle:RepairOrder')->find($id);

		///chequear repair_order_fix si la version es 1 es primera vez que se repara el dispositivo
		$checkRepairInsert = $em->getRepository('SolucelAdminBundle:RepairOrder')->checkRepairInsert($id);
		//var_dump($checkRepairInsert);die;


		//chequear garantia
		$entity = $em->getRepository('SolucelAdminBundle:RepairOrderFix')->findByRepairOrder($id);
		$entity = $entity[0];
		//var_dump($entity);die;

		$fixes = $em->getRepository('SolucelAdminBundle:RepairOrderDeviceFixType')->findByRepairOrder($id);
		/*
		print "<pre>";
		var_dump($fixes);die;
		 * */
		$replacements = $em->getRepository('SolucelAdminBundle:RepairOrderDeviceReplacement')->findByRepairOrder($id);
        $accessories = $em->getRepository('SolucelAdminBundle:RepairOrderDeviceAccessory')->findByRepairOrder($id);
        $strAccessories = "";
        if($accessories){
            foreach ($accessories as $element ){
                $strAccessories .= $element->getDeviceAccessory()->getName().",";
            }
        }

        $defects = $em->getRepository('SolucelAdminBundle:RepairOrderDeviceDefect')->findByRepairOrder($id);
        $strDefects = "";
        if($defects){
            foreach ($defects as $element ){
                $strDefects .= $element->getDeviceDefect()->getName().",";
            }
        }
		//var_dump($checkRepairInsert);die;

		if($checkRepairInsert){
			//cambiar status de orden & insert - repiar_order_status

			$status = $em->getRepository('SolucelAdminBundle:RepairStatus')->findByName("EN REPARACION");
			$status = $status[0];

			$objOrder->setRepairStatus($status);
			$objOrder->setUpdatedAtValue();
		    $em->persist($objOrder);
		    $em->flush();


			$objRepairStatus = new RepairOrderStatus();
			$objRepairStatus->setCreatedAtValue();
			$objOrder = $em->getRepository('SolucelAdminBundle:RepairOrder')->find($id);
			$objRepairStatus->setRepairOrder($objOrder);
			$objRepairStatus->setRepairStatus($status);
			$objRepairStatus->setCreatedBy($user);
		    $em->persist($objRepairStatus);
		    $em->flush();

		}

        //$orderStatus = $objOrder->getRepairStatus()->getName();
        $orderStatus = $em->getRepository('SolucelAdminBundle:RepairOrder')->getOrderStatus($id);

        $deleteForm = $this->createDeleteForm($entity);
		$editForm = $this->createEditForm($entity);

        $editForm->handleRequest($request);
		//print "llega";die;
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
			$entity->setUpdatedAtValue();
            $em->persist($entity);
            $em->flush();

            return $this->redirectToRoute('solucel_admin_repairorderfix_edit', array('id' => $id));
        }


		$checkRepairInsert = $checkRepairInsert ? 1 : 0;


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


			foreach ($objQualityRegisters as $register) {
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

		/*
		print "<pre>";
		//var_dump($replacements);die;

		foreach ($replacements as $replacement) {
			print $replacement->getQuantity()."XX";
			print $replacement->getPrice();
			print "___";
		}
		die;
		 * */


		$deviceBrand = $em->getRepository('SolucelAdminBundle:DeviceBrand')->findAll();
		
		//var_dump($entity->getHasWarranty());die;

        return $this->render('SolucelAdminBundle:RepairOrderFix:edit.html.twig', array(
            'entity' => $entity,
            'fixes' => $fixes,
            'replacements' => $replacements,
            'strAccessories' => $strAccessories,
            'strDefects' => $strDefects,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'order_status' => $orderStatus["name"],
            'orderID' => $id,
            'checkRepairInsert' => $checkRepairInsert,
            'objQualityControl' => $objQualityControl,
            'arrRegisters' => $arrRegisters,
            'arrGroups' => $arrGroups,
            'deviceBrand' => $deviceBrand

        ));
    }

    /**
     * Deletes a RepairOrderFix entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
    	$this->get("services")->setVars('repairOrderFix');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:RepairOrderFix')->find($id);
        $form = $this->createDeleteForm($entity);
        $form->handleRequest($request);

        //if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SolucelAdminBundle:RepairOrderFix')->find($entity);


        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RepairOrderFix entity.');
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
                    return $this->redirectToRoute('solucel_admin_repairorderfix_index');
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
        return $this->redirectToRoute('solucel_admin_repairorderfix_index');
    }

    /**
     * Creates a form to delete a RepairOrderFix entity.
     *
     * @param RepairOrderFix The RepairOrderFix entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($entity)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('solucel_admin_repairorderfix_delete', array('id' => $entity->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }




    /**
     * Creates a new RepairOrderFix entity.
     *
     */
    public function createAction(Request $request)
    {
		$this->get("services")->setVars('repairOrderFix');
        $entity = new RepairOrderFix();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
		/*print "<pre>";
		var_dump($form->getErrorsAsString());die;
		 * */

        if ($form->isValid()) {
        	$em = $this->getDoctrine()->getManager();


			$entity->setCreatedAtValue();
			$entity->setUpdatedAtValue();

            $em->persist($entity);
            $em->flush();


			$this->get('services')->flashSuccess($request);
            return $this->redirect($this->generateUrl('solucel_admin_repairorderfix_index'));

        }
		/*
		else{
			print "FORMULARIO NO VALIDO";DIE;
		}
		 * */

        return $this->render('SolucelAdminBundle:RepairOrderFix:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a RepairOrderFix entity.
     *
     * @param RepairOrderFix $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm($entity)
    {
    	//$this->setVars();
        $form = $this->createForm(RepairOrderFixType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_repairorderfix_create'),
            'method' => 'POST'
            //'client' => $this->userLogged,
        ));


        return $form;
    }




    /**
    * Creates a form to edit a RepairOrderFix entity.
    *
    * @param RepairOrderFix $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm($entity)
    {
    	//$this->setVars();
        $form = $this->createForm(RepairOrderFixType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_repairorderfix_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            //'client' => $this->userLogged,
        ));


        return $form;
    }


    /**
     * Edits an existing RepairOrderFix entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
    	$this->get("services")->setVars('repairOrderFix');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:RepairOrderFix')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RepairOrderFix entity.');
        }

		$version = $entity->getVersion() + 1;
		$entity->setVersion($version);
		$entity->setUpdatedAtValue();

        /*
		print "<pre>";
		var_dump($_REQUEST);DIE;
        */



		$order = $entity->getRepairOrder();
		$order->setHumidity(intval($_REQUEST["repair_order"]["humidity"]));
		
		//campos para motorola		
		if(isset($_REQUEST["repairomoto"])){
			$repairoMotoValues = $_REQUEST["repairomoto"];
			//var_dump($repairoValues);die;
			$order->setDeviceMsnOut($repairoMotoValues["device_msn_out"]);
			
		}

        $repairoValues = $_REQUEST["repairo"];
        $order->setDeviceImeiOut($repairoValues["device_imei_out"]);
        $order->setDeviceImei2Out($repairoValues["device_imei2_out"]);

		

		//FIXES
		$cleanFixes = $em->getRepository('SolucelAdminBundle:RepairOrder')->cleanOrderFixes($order->getId());
		if(isset($_REQUEST["fix"])){
			$arrFix = $_REQUEST["fix"];

			//Fixes
			foreach ($arrFix as $key => $value) {
				$objFix = new RepairOrderDeviceFixType();
				$objFix->setRepairOrder($order);
				$objFix->setDeviceFixType($em->getRepository('SolucelAdminBundle:DeviceFixType')->find($value));
	            $em->persist($objFix);

			}
			$em->flush();

		}

		//REPLACEMENTS
		if($entity->getOrderConfirmation() == 0){

			$cleanReplacements = $em->getRepository('SolucelAdminBundle:RepairOrder')->cleanOrderReplacements($order->getId());
			if(isset($_REQUEST["replacement"])){
				$arrReplacement = $_REQUEST["replacement"];

				/*
				print "<pre>";
				var_dump($arrReplacement);die;*/

				//Replacements

				foreach ($arrReplacement as $key => $value) {
					$objAux = $em->getRepository('SolucelAdminBundle:DeviceReplacement')->find(intval($value["id"]));

					if($objAux != NULL){
						$objReplacement = new RepairOrderDeviceReplacement();
						$objReplacement->setRepairOrder($order);
						$objReplacement->setDeviceReplacement($objAux);
						$objReplacement->setQuantity($value["quantity"]);

			            $em->persist($objReplacement);

					}

				}

				$em->flush();
			}
		}






		/*
		print "<pre>";
		var_dump($_REQUEST);die;
		 * */


        $deleteForm = $this->createDeleteForm($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

		//die((string) $editForm->getErrors(true));
        if ($editForm->isValid()) {





            //requisitionNumber
            if(isset($_REQUEST["repair_order_fix"]["requisitionNumber"])){

                $entity->setRequisitionNumber(trim($_REQUEST["repair_order_fix"]["requisitionNumber"]));

            }
            $em->persist($entity);
	        $em->flush();

			$this->get('services')->flashSuccess($request);
	        //return $this->redirect($this->generateUrl('solucel_admin_repairorderfix_index', array('id' => $id)));
	        return $this->redirectToRoute('solucel_admin_repairorderfix_edit', array('id' => $order->getId()));
	        //return $this->redirect('solucel_admin_repairorderfix_edit', array('id' => $id));


        }
		else{

			//NOT VALID FORM
			$this->get('services')->flashWarning($request);
	        //return $this->redirect($this->generateUrl('solucel_admin_repairorderfix_index', array('id' => $id)));
	        return $this->redirectToRoute('solucel_admin_repairorderfix_edit', array('id' => $order->getId()));

		}
		/*
        return $this->render('SolucelAdminBundle:RepairOrderFix:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
		 * */
    }



	public function addFixAction(Request $request){


		$em = $this->getDoctrine()->getManager();
		$entities = $em->getRepository('SolucelAdminBundle:DeviceFixType')->findBy(array(), array("name" => "ASC"));

		$strOption = '<option value=""></option>>';
		$count = intval($_REQUEST["count"]) +1;
		$strTCinputs = "";

		foreach ($entities as $fix) {
		    $transactionCode = 0;
		    if($fix->getTransactionCode() != NULL){
                $transactionCode = $fix->getTransactionCode()->getId();
            }

			$strOption .= '<option value="'.$fix->getId().'">'.$fix->getName().' /Nivel-'.$fix->getDeviceFixLevel()->getName().'</option>';
            $strTCinputs .= '<input type="hidden" value="'.$transactionCode.'" id="tc_'.$fix->getId().'"/>';
		}


		$tr	 = '<tr id="tr_repair_order_fix_'.$count.'">' .
					'<td>'.
						'<label class="required" for="repair_order_fix">Reparación '.$count.'&nbsp;</label>'.'<span style="cursor:pointer" onclick="deleteFix('.$count.');" class="label label-danger arrowed arrow-left">Eliminar</span>'.
						'<select id="repair_order_fix_'.$count.'" name="fix['.$count.']" class="required select2-search" onchange="changeTransactionCode(this.value);">'.
							$strOption.
						'</select>'.
					'</td>'.

				'</tr>';

		//$tr .= '$("#repair_order_fix_'.$count.'").rules("add", "required")';

		print $tr.$strTCinputs;
		die;

	}

	public function addReplacementAction(Request $request){


		////GROUP BY TYPE IN SELECT


		$em = $this->getDoctrine()->getManager();
		//$entities = $em->getRepository('SolucelAdminBundle:DeviceReplacement')->findAll();

		$strOption = "";
		$count = intval($_REQUEST["count"]);

		/*
		foreach ($entities as $replacement) {
			$strOption .= '<option value="'.$replacement->getId().'">'.$replacement->getName().' /Tipo-'.$replacement->getDeviceReplacementType()->getName().'</option>';
		}
		 * */


		$optionsQuantity = "";

		for ($i=1; $i <= 10; $i++) {
			$optionsQuantity .= "<option value='{$i}'>Cantidad = {$i}</option>";
		}

		/*
		$tr	 = '<tr id="tr_repair_order_replacement_'.$count.'">' .
					'<td>'.
						'<label class="required" for="repair_order_replacement">Repuesto '.$count.'&nbsp;</label>'.'<span style="cursor:pointer" onclick="deleteReplacement('.$count.');" class="label label-danger arrowed arrow-left">Eliminar</span>'.
						'<select id="repair_order_replacement_'.$count.'" name="replacement['.$count.'][id]" class="select2-search">'.
							$strOption.
						'</select>'.
						/*'<input id="repair_order_replacement_'.$count.'" name="replacement['.$count.'][id]" class="select2-search">'.*/
						/*'<select id="repair_order_fix_quantity_'.$count.'" name="replacement['.$count.'][quantity]" class="select2-search">'.
							$optionsQuantity.
						'</select>'.
					'</td>'.

				'</tr>';
						 *
						 */

		$tr	 = '<tr id="tr_repair_order_replacement_'.$count.'">' .
					'<td>'.
						'<label class="required" for="repair_order_replacement">Repuesto '.$count.'&nbsp;</label>'.'<span style="cursor:pointer" onclick="deleteReplacement('.$count.');" class="label label-danger arrowed arrow-left">Eliminar</span><br>'.
						'<input class="form-control" id="repair_order_replacement_'.$count.'" />'.
						'<input type="hidden" id="hidden_repair_order_replacement_'.$count.'" name="replacement['.$count.'][id]" />'.
						'<select id="repair_order_fix_quantity_'.$count.'" name="replacement['.$count.'][quantity]" class="select2-search">'.
							$optionsQuantity.
						'</select>'.
					'</td>'.

				'</tr>';

				/*
		$autocomplete = '<script>$( "#repair_order_replacement_'.$count.'" ).autocomplete({'.
							'dataType:"html",'.
							'source: "{{ path('."solucel_admin_repairorderfix_replacementfind".') }}",'.
						      'minLength: 2,'.
						      'select: function( event, ui ) {'.
						      		'console.log(this.id);'.
								'replacementID = ui.item.id;'.

								'console.log(replacementID);'.
						      '}'.
						    '})</script>;';
				 *
				 */

		print $tr;
		die;

	}


	//public function replacementFindAction(Request $request, $model){//use to be by model
		public function replacementFindAction(Request $request, $brand){//brandID


        $term = trim(strip_tags($request->get('term')));

        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SolucelAdminBundle:RepairOrder')->findReplacement($term, $brand);
        $response = new JsonResponse();
        $response->setData($entities);

        return $response;
    }


	public function finishRepairmentAction(Request $request, $id){

		//id RepairOrderFix

		$this->get("services")->setVars('repairOrderFix');
    	$session = new Session();
		$em = $this->getDoctrine()->getManager();
		$user = $session->get('user_logged');


		$objOrderFix =  $em->getRepository('SolucelAdminBundle:RepairOrderFix')->find($id);
		$objOrder =  $em->getRepository('SolucelAdminBundle:RepairOrder')->find($objOrderFix->getRepairOrder()->getId());


		///orden
		$status = $em->getRepository('SolucelAdminBundle:RepairStatus')->findByName("CONTROL DE CALIDAD TECNICO");
		//cambiar status de orden (insert - repiar_order_status)
		$objOrder->setRepairStatus($status[0]);
		$objOrder->setUpdatedAtValue();
		$em->persist($objOrder);
		$em->flush();

		///CREAR REPAIRORDERQUALITYCONTROL
		//chequear si ya existe si no crear
		$objQualityControl = $em->getRepository('SolucelAdminBundle:RepairOrderQualityControl')->findByRepairOrder($objOrder->getId());

		if($objQualityControl){
			$objQualityControl = $objQualityControl[0];
		}
		else{
			$objQualityControl = new RepairOrderQualityControl();
		}

		$objQualityControl->setVersion(0);
		$objQualityControl->setRepairOrder($objOrder);
		$objQualityControl->setQualityApproved(0);
		$objQualityControl->setCreatedAtValue();
		$objQualityControl->setCreatedBy($user);
		$em->persist($objQualityControl);

		$em->flush();




		//log status
		$objRepairStatus = new RepairOrderStatus();
		$objRepairStatus->setCreatedAtValue();
		$objRepairStatus->setRepairOrder($objOrder);
		$objRepairStatus->setRepairStatus($status[0]);
		$objRepairStatus->setCreatedBy($user);
	    $em->persist($objRepairStatus);
	    $em->flush();

		return $this->redirectToRoute('solucel_admin_repairorderfix_index');

	}


	public function unfixRepairmentAction(Request $request, $id){

		//id RepairOrderFix

		$this->get("services")->setVars('repairOrderFix');
    	$session = new Session();
		$em = $this->getDoctrine()->getManager();
		$user = $session->get('user_logged');


		$objOrderFix =  $em->getRepository('SolucelAdminBundle:RepairOrderFix')->find($id);
		$objOrder =  $em->getRepository('SolucelAdminBundle:RepairOrder')->find($objOrderFix->getRepairOrder()->getId());


		///orden
		$status = $em->getRepository('SolucelAdminBundle:RepairStatus')->findByName("IRREPARABLE");//estos tambien van en FINALIZADO
		//cambiar status de orden (insert - repiar_order_status)
		$objOrder->setRepairStatus($status[0]);
		//print "entra";die;
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

		$objEntryDate = $objOrder->getEntryDate()->format('Y-m-d H:i:s');
		//$this->get("services")->finishedOrderMail($objOrder->getClient()->getEmail(), $objOrder->getTicketNumber(), $objOrder->getDeviceBrand()->getName(), $objOrder->getDeviceModel()->getName());
		$this->get("services")->finishedOrderMail($objOrder->getClient()->getEmail(), $objOrder->getTicketNumber(),  $objEntryDate);

		$this->get('services')->flashSuccess($request);
		return $this->redirectToRoute('solucel_admin_repairorderfix_index');

	}


	public function changeDeviceRepairmentAction(Request $request, $id){
		//id RepairOrderFix



		$this->get("services")->setVars('repairOrderFix');
    	$session = new Session();
		$em = $this->getDoctrine()->getManager();
		$user = $session->get('user_logged');


		$objOrderFix =  $em->getRepository('SolucelAdminBundle:RepairOrderFix')->find($id);
		$objOrder =  $em->getRepository('SolucelAdminBundle:RepairOrder')->find($objOrderFix->getRepairOrder()->getId());


		///orden
		$status = $em->getRepository('SolucelAdminBundle:RepairStatus')->findByName("CAMBIO POR GARANTIA");//estos tambien van en FINALIZADO
		//cambiar status de orden (insert - repiar_order_status)
		$objOrder->setRepairStatus($status[0]);
		//print "entra";die;

		$myRequest = $_REQUEST["repair_order"];




		$brand = $em->getRepository('SolucelAdminBundle:DeviceBrand')->find($myRequest["deviceChangeBrand"]);
		$objOrder->setDeviceChangeBrand($brand);

		$model = $em->getRepository('SolucelAdminBundle:DeviceModel')->find($myRequest["deviceChangeModel"]);
		$objOrder->setDeviceChangeModel($model);

		$objOrder->setDeviceChangeImei($myRequest["deviceChangeImei"]);
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

		$objEntryDate = $objOrder->getEntryDate()->format('Y-m-d H:i:s');
		//$this->get("services")->finishedOrderMail($objOrder->getClient()->getEmail(), $objOrder->getTicketNumber(), $objOrder->getDeviceBrand()->getName(), $objOrder->getDeviceModel()->getName());
		$this->get("services")->finishedOrderMail($objOrder->getClient()->getEmail(), $objOrder->getTicketNumber(),  $objEntryDate);
		$this->get('services')->flashSuccess($request);
		return $this->redirectToRoute('solucel_admin_repairorderfix_index');



	}


	public function pendingStatusAction(Request $request){

		//var_dump($_REQUEST);die;

		$id = intval($_REQUEST["id"]);
		//id is from RepairOrderFix

		$this->get("services")->setVars('repairOrderFix');
    	$session = new Session();
		$em = $this->getDoctrine()->getManager();
		$user = $session->get('user_logged');

		$objOrderFix = $em->getRepository('SolucelAdminBundle:RepairOrderFix')->find($id);
		$objOrder = $em->getRepository('SolucelAdminBundle:RepairOrder')->find($objOrderFix->getRepairOrder()->getId());
		//$objOrder = $objOrder[0];

		$newStatus = "PENDIENTE POR REPUESTO";
		$status = $em->getRepository('SolucelAdminBundle:RepairStatus')->findByName($newStatus);
		$status = $status[0];


		$objOrder->setRepairStatus($status);
		$objOrder->setUpdatedAtValue();
		$em->persist($objOrder);
		$em->flush();

		$objRepairOrderStatus = new RepairOrderStatus();
		$objRepairOrderStatus->setCreatedAtValue();
		$objRepairOrderStatus->setRepairStatus($status);
		$objRepairOrderStatus->setRepairOrder($objOrder);

		$user = $em->getRepository('SolucelAdminBundle:User')->find($user->getId());
		$objRepairOrderStatus->setCreatedBy($user);
		
		
		$this->sendPendingStatusMailClient($objOrder->getId(), $objOrder->getTicketNumber(), $objOrder->getClient()->getEmail(), $objOrder->getCreatedAt()->format("Y-m-d H:i:s"));
		

		$em->persist($objRepairOrderStatus);
		$em->flush();

		return new JsonResponse(array("result" => true));


	}


}

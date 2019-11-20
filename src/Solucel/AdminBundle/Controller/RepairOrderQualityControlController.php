<?php

namespace Solucel\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Session;


use Solucel\AdminBundle\Entity\RepairOrder;
use Solucel\AdminBundle\Entity\RepairOrderFix;
use Solucel\AdminBundle\Entity\Notification;
use Solucel\AdminBundle\Entity\RepairOrderStatus;
use Solucel\AdminBundle\Entity\RepairOrderQualityControl;
use Solucel\AdminBundle\Entity\RepairOrderQualityControlRegister;
use Solucel\AdminBundle\Form\RepairOrderQualityControlType;

/**
 * RepairOrderQualityControl controller.
 *
 */
class RepairOrderQualityControlController extends Controller
{
    /**
     * Lists all RepairOrderQualityControl entities.
     *
     */
    public function indexAction()
    {
    	$session = new Session();
    	$this->get("services")->setVars('repairOrderQualityControl');
		
		$user = $session->get('user_logged');
		$serviceID = $user->getServiceCenter() == null ? 0 : $user->getServiceCenter()->getId();
		
        $em = $this->getDoctrine()->getManager();

        //$entities = $em->getRepository('SolucelAdminBundle:RepairOrderQualityControl')->findAll();
        $entities = $em->getRepository('SolucelAdminBundle:RepairOrder')->getQualityControlOrders($serviceID);
		

        return $this->render('SolucelAdminBundle:RepairOrderQualityControl:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new RepairOrderQualityControl entity.
     *
     */
    public function newAction(Request $request)
    {
    	$this->get("services")->setVars('repairOrderQualityControl');
        $entity = new RepairOrderQualityControl();
        $form   = $this->createCreateForm($entity);
		 
	
        return $this->render('SolucelAdminBundle:RepairOrderQualityControl:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

 

    /**
     * Finds and displays a RepairOrderQualityControl entity.
     *
     */
    public function showAction($entity)
    {
        $deleteForm = $this->createDeleteForm($entity);

        return $this->render('solucel_admin_repairorderqualitycontrol/show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing RepairOrderQualityControl entity.
     *
     */
    public function editTechAction(Request $request, $id)
    {
    	
		
		$this->get("services")->setVars('repairOrderQualityControl');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:RepairOrderQualityControl')->findByRepairOrder($id);
		$entity = $entity[0];

		$objRepairFix = $em->getRepository('SolucelAdminBundle:RepairOrderFix')->findByRepairOrder($entity->getRepairOrder()->getId());
        $objRepairFix = $objRepairFix[0];

		$groups = $em->getRepository('SolucelAdminBundle:QualityControlGroup')->findAll();
		
		$arrGroups = array();
		
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

		/*
		print "<pre>";
		var_dump($arrGroups);die;
		 * */
		


        return $this->render('SolucelAdminBundle:RepairOrderQualityControl:editTech.html.twig', array(
            'entity' => $entity,
            'arrGroups' => $arrGroups,
            'objRepairFix' => $objRepairFix,
            //'form' => $editForm->createView(),
            //'delete_form' => $deleteForm->createView(),
        ));
    }


    public function editQualityControlAction(Request $request, $id)
    {
    	
		
		$this->get("services")->setVars('repairOrderQualityControl');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:RepairOrderQualityControl')->find($id);

        $objRepairFix = $em->getRepository('SolucelAdminBundle:RepairOrderFix')->findByRepairOrder($entity->getRepairOrder()->getId());
        $objRepairFix = $objRepairFix[0];
		
		$qualityControlRegisters = $em->getRepository('SolucelAdminBundle:RepairOrderQualityControlRegister')->findByRepairOrderQualityControl($id);
		$arrRegisters = array();
		
		foreach ($qualityControlRegisters as $register) {
			$group = $register->getQualityControlGroup()->getId();
			$subgroup = $register->getQualityControlSubGroup()->getId();
			
			if(!isset($arrRegisters[$group])){
				$arrRegisters[$group] = array();	
				$arrRegisters[$group] = array();
				$arrRegisters[$group]["name"] = $register->getQualityControlGroup()->getName();
				$arrRegisters[$group]["id"] = $register->getQualityControlGroup()->getId();
			}
			if(!isset($arrRegisters[$group][$subgroup])){
				$arrRegisters[$group][$subgroup] = array();
				$arrRegisters[$group][$subgroup]["name"] = $register->getQualityControlSubGroup()->getName();
				$arrRegisters[$group][$subgroup]["id"] = $register->getQualityControlSubGroup()->getId();
				
			}
			
			//print "X$subgroup";
			$arrRegisters[$group][$subgroup]["check"]  = $register->getTechnicianCheck();
			$arrRegisters[$group][$subgroup]["uncheck"] = $register->getTechnicianUncheck();
			$arrRegisters[$group][$subgroup]["not_apply"] = $register->getNotApply();
			
			//
			
		}
		
		/*
		print "<pre>";
		var_dump($arrRegisters);die;
		 * */
		
		
		
		 /*
		$groups = $em->getRepository('SolucelAdminBundle:QualityControlGroup')->findAll();
		
		$arrGroups = array();
		
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
		  * */

		/*
		print "<pre>";
		var_dump($arrGroups);die;*/
		
		


        return $this->render('SolucelAdminBundle:RepairOrderQualityControl:editQualityControl.html.twig', array(
            'entity' => $entity,
            //'arrGroups' => $arrGroups,
            'arrRegisters' => $arrRegisters,
            'objRepairFix' => $objRepairFix,
            //'form' => $editForm->createView(),
            //'delete_form' => $deleteForm->createView(),
        ));
    }
	
    /**
     * Deletes a RepairOrderQualityControl entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
    	
		$this->get("services")->setVars('repairOrderQualityControl');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:RepairOrderQualityControl')->find($id);		
        $form = $this->createDeleteForm($entity);
        $form->handleRequest($request);

        //if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SolucelAdminBundle:RepairOrderQualityControl')->find($entity);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find RepairOrderQualityControl entity.');
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
                        return $this->redirectToRoute('solucel_admin_repairorderqualitycontrol_index');
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
        return $this->redirectToRoute('solucel_admin_repairorderqualitycontrol_index');
    }

    /**
     * Creates a form to delete a RepairOrderQualityControl entity.
     *
     * @param RepairOrderQualityControl The RepairOrderQualityControl entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('solucel_admin_repairorderqualitycontrol_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
	
	
	
	
    /**
     * Creates a new RepairOrderQualityControl entity.
     *
     */
    public function createAction(Request $request)
    {
		
		$session = new Session();
		
        $entity = new RepairOrderQualityControl();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
		$user = $session->get('user_logged');
		/*print "<pre>";
		var_dump($form->getErrorsAsString());die;
		 * */
		 
        if ($form->isValid()) {
        	$em = $this->getDoctrine()->getManager();
			
			$entity->setCreatedBy($user);
			$entity->setCreatedAtValue();
            $em->persist($entity);
            $em->flush();


			$this->get('services')->flashSuccess($request);
            return $this->redirect($this->generateUrl('solucel_admin_repairorderqualitycontrol_index'));
			 
        }
		/*
		else{
			print "FORMULARIO NO VALIDO";DIE;
		}*/

        return $this->render('SolucelAdminBundle:RepairOrderQualityControl:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a RepairOrderQualityControl entity.
     *
     * @param RepairOrderQualityControl $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm($entity)
    {
    	//$this->setVars();
        $form = $this->createForm(RepairOrderQualityControlType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_repairorderqualitycontrol_create'),
            'method' => 'POST'
            //'client' => $this->userLogged,
        ));



        return $form;
    }	
	

	
	
    /**
    * Creates a form to edit a RepairOrderQualityControl entity.
    *
    * @param RepairOrderQualityControl $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm($entity)
    {
    	//$this->setVars();
        $form = $this->createForm(RepairOrderQualityControlType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_repairorderqualitycontrol_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            //'client' => $this->userLogged,
        ));


        return $form;
    }
	
	
    /**
     * Edits an existing RepairOrderQualityControl entity.
     *
     */
    public function updateTechAction(Request $request, $id)
    {
    	
		/*
    	print "<pre>";
		var_dump($_REQUEST);DIE;
		 * */
		
		
    	$this->get("services")->setVars('repairOrderQualityControl');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:RepairOrderQualityControl')->find($id);
		
		

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RepairOrderQualityControl entity.');
        }
		
		
		//qualityControl
		$entity->setVersion(1);
		//$techComment =  $entity->getTechComment()."- \n".date("Y/m/d")." ".trim($_REQUEST["quality"]["tech_comment"]);
		$techComment =  "- \n".date("Y/m/d")." ".trim($_REQUEST["quality"]["tech_comment"]);
		$entity->setTechComment($techComment);
		$em->persist($entity);
		$em->flush();
		
		$arrCheck = $_REQUEST["check"];
		
		//qualityControlRegister
		foreach ($arrCheck as $key => $value) {
			
			$keys = explode("_", $key);
			//var_dump($keys);die;
			
			$group = $keys[0];
			$subgroup = $keys[1];			
			
			$objQualityControlRegister = $em->getRepository('SolucelAdminBundle:RepairOrderQualityControlRegister')
				->findBy(array("repairOrderQualityControl" => $id, "qualityControlGroup" => $group, "qualityControlSubGroup" => $subgroup));
			
			if($objQualityControlRegister){
				
				$objQualityControlRegister = $objQualityControlRegister[0];
				//var_dump($objQualityControlRegister);
				//print "entar1";die;	
			}
			else{
				//print "entar2";die;
				$objQualityControlRegister = new RepairOrderQualityControlRegister();
				$objQualityControlRegister->setRepairOrderQualityControl($entity);
				$objQualityControlRegister->setQualityControlGroup($em->getRepository('SolucelAdminBundle:QualityControlGroup')->find($group));
				$objQualityControlRegister->setQualityControlSubGroup($em->getRepository('SolucelAdminBundle:QualityControlSubGroup')->find($subgroup));
					
			}
		
			//actualizar el mismo registro anterior
			
			$objQualityControlRegister->setTechnicianCheck(0);
			$objQualityControlRegister->setTechnicianUncheck(0);
			$objQualityControlRegister->setNotApply(0);
						
			if($value == "check"){//funciona
				$objQualityControlRegister->setTechnicianCheck(1);
				
			}elseif($value == "uncheck"){//no funciona
				$objQualityControlRegister->setTechnicianUncheck(1);
			
			}elseif($value == "na"){//no aplica			
				$objQualityControlRegister->setNotApply(1);
			}
			
			$em->persist($objQualityControlRegister);
			$em->flush();
			
		}
		
		$objOrder = $em->getRepository('SolucelAdminBundle:RepairOrder')->find($entity->getRepairOrder());
		$status = $em->getRepository('SolucelAdminBundle:RepairStatus')->findByName("CONTROL DE CALIDAD");
		//cambiar status de orden (insert - repiar_order_status)
		$objOrder->setRepairStatus($status[0]);
		$objOrder->setUpdatedAtValue();
		$em->persist($objOrder);
		$em->flush();
	
			
		$this->get('services')->flashSuccess($request);
        return $this->redirectToRoute('solucel_admin_repairorderfix_index');

    }	


    public function updateQualityControlAction(Request $request, $id)
    {
		/*
    	print "<pre>";
		var_dump($_REQUEST);DIE;
		 * 
		 */
		
		
		$session = new Session();
    	$this->get("services")->setVars('repairOrderQualityControl');
        $em = $this->getDoctrine()->getManager();
		$user = $session->get('user_logged');

        $entity = $em->getRepository('SolucelAdminBundle:RepairOrderQualityControl')->find($id);
		$orderID = $entity->getRepairOrder()->getId();
		$orderTicketNumber = $entity->getRepairOrder()->getTicketNumber();
		//print $orderID;die;
		$objOrder = $em->getRepository('SolucelAdminBundle:RepairOrder')->find($orderID);
		

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RepairOrderQualityControl entity.');
        }
		
		
		//qualityControl
		$entity->setVersion(2);
		$qaComment =  $entity->getQaComment()." \n".date("Y/m/d")." ".trim($_REQUEST["quality"]["qa_comment"]);
		$ccUserID = $this->get("services")->getUser()->getId();
		$ccUser = $em->getRepository('SolucelAdminBundle:User')->find($ccUserID);
		
		$entity->setCreatedBy($ccUser);
		
		//$em->persist($entity);
		//$em->flush();
	
		//revisar si paso el control de calidad
		//si no pasa regresar a CONTROL DE CALIDAD TECNICO
		//si pasa FINALIZADO
		
		$arrTech = $_REQUEST["tech"];
		//$arrTech = 
		$arrQA = $_REQUEST["qa"];
		
		$qualityControl = 1;//1 si pasa el control de calidad, si un registro no coincide deberá de ser 0
		
		foreach ($arrQA as $key => $value) {

			$keys = explode("_", $key);
			//var_dump($keys);die;
			
			$group = $keys[0];
			$subgroup = $keys[1];

			$objQualityControlRegister = $em->getRepository('SolucelAdminBundle:RepairOrderQualityControlRegister')
				->findBy(array("repairOrderQualityControl" => $id, "qualityControlGroup" => $group, "qualityControlSubGroup" => $subgroup));
			
			$objQualityControlRegister = $objQualityControlRegister[0];

			$objQualityControlRegister->setQualityCheck(0);
			$objQualityControlRegister->setQualityUncheck(0);
			$objQualityControlRegister->setNotApply(0);
			
			if($value == "check"){//funciona
				$objQualityControlRegister->setQualityCheck(1);	
			}elseif($value == "uncheck"){//no funciona
				$objQualityControlRegister->setQualityUncheck(1);
			}elseif($value == "na"){//no aplica
				$objQualityControlRegister->setNotApply(1);
			}
							
			
			if($arrTech[$key] != $arrQA[$key]){
				$qualityControl = 0;/// no coincide un registro
			}
				
		}
		
		
		
		if($qualityControl == 0){
			//status CONTROL DE CALIDAD TECNICO
			$status = $em->getRepository('SolucelAdminBundle:RepairStatus')->findByName("EN REPARACION");
			$status = $status[0];
			$request->getSession()->getFlashBag()->add('warning','La orden regresó a REPARACION');
			$entity->setQualityApproved(0);	
			
			//generar notificacion para el tecnico
			$objRepairOrderFix = $em->getRepository('SolucelAdminBundle:RepairOrderFix')->findByRepairOrder($orderID);
			$objRepairOrderFix = $objRepairOrderFix[0];
						
			$notification = new Notification();
			$tech = $em->getRepository('SolucelAdminBundle:User')->find($objRepairOrderFix->getAssignedTo());
			$notification->setUser($tech);
			
			$ccUserID = $this->get("services")->getUser()->getId();
			$ccUser = $em->getRepository('SolucelAdminBundle:User')->find($ccUserID);
			$notification->setCreatedBy($ccUser);
			$notification->setUpdatedBy($ccUser);
			
			$notification->setName("Retorno - Control de calidad");
			//$notification->setDescription("Orden #".$orderID." ". $qaComment);
			$notification->setDescription("Orden #".$orderTicketNumber." ". $qaComment);
			$notification->setEnabled(1);
			$notification->setAlreadyRead(0);
			
			$notification->setCreatedAtValue();
			$notification->setUpdatedAtValue();
			$em->persist($notification);
			$em->flush();				
						
		}
		else{
			//ORDEN FINALIZADA
			$status = $em->getRepository('SolucelAdminBundle:RepairStatus')->findByName("FINALIZADO");
			$status = $status[0];
			$entity->setQualityApproved(1);
			//$this->get('services')->flashSuccess($request);
			
			
			$objEntryDate = $objOrder->getEntryDate()->format('Y-m-d H:i:s');
			//$this->get("services")->finishedOrderMail($objOrder->getClient()->getEmail(), $objOrder->getTicketNumber(), $objOrder->getDeviceBrand()->getName(), $objOrder->getDeviceModel()->getName());
			$this->get("services")->finishedOrderMail($objOrder->getClient()->getEmail(), $objOrder->getTicketNumber(),  $objEntryDate);
							
			
			$request->getSession()->getFlashBag()->add('success','La orden está FINALIZADA');
		}
		
		
		///GUARDAR QUALITY CONTROL
		$entity->setQaComment($qaComment);
		$em->persist($entity);
		$em->flush();	
				
		//cambio status en RepairOrder
		$objOrder->setRepairStatus($status);
		$objOrder->setUpdatedAtValue();
		$em->persist($objOrder);
		$em->flush();
		
		//nuevo registro en log de status
		$objRepairOrderStatus = new RepairOrderStatus();
		$objRepairOrderStatus->setCreatedAtValue();
		$objRepairOrderStatus->setRepairStatus($status);
		$objRepairOrderStatus->setRepairOrder($objOrder);
		
		$user = $em->getRepository('SolucelAdminBundle:User')->find($user->getId()); 			
		$objRepairOrderStatus->setCreatedBy($user);
		
		$em->persist($objRepairOrderStatus);
		$em->flush();
				

			
		
        return $this->redirectToRoute('solucel_admin_repairorderqualitycontrol_index');

    }	





}

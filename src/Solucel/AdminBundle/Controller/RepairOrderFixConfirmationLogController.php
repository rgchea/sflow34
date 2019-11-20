<?php

namespace Solucel\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Session;


use Solucel\AdminBundle\Entity\RepairOrderFixConfirmationLog;

use Solucel\AdminBundle\Entity\RepairOrderStatus;
use Solucel\AdminBundle\Form\RepairOrderFixConfirmationLogType;

/**
 * RepairOrderFixConfirmationLog controller.
 *
 */
class RepairOrderFixConfirmationLogController extends Controller
{
    /**
     * Lists all RepairOrderFixConfirmationLog entities.
     *
     */
    public function indexAction()
    {
 		//print "die";die;
		$this->get("services")->setVars('repairOrderFixConfirmationLog');
    	$session = new Session();
    			
        $em = $this->getDoctrine()->getManager();
		$user = $session->get('user_logged');
		
		
		$serviceCenter = $user->getServiceCenter() ==  NULL ? 0 : $user->getServiceCenter()->getId();
		$operator = $user->getOperator() ==  NULL ? 0 : $user->getOperator()->getId();
		$brand = $user->getDeviceBrand() ==  NULL ? 0 : $user->getDeviceBrand()->getId();
    			
		$entities = $em->getRepository('SolucelAdminBundle:RepairOrder')->getRepairOrderConfirmation( $serviceCenter, $operator, $brand );
		
        //$entities = $em->getRepository('SolucelAdminBundle:RepairOrder')->getOrders();
		
        return $this->render('SolucelAdminBundle:RepairOrderFixConfirmationLog:index.html.twig', array(
            'entities'       => $entities,            
			
        ));	
    }

    /**
     * Creates a new RepairOrderFixConfirmationLog entity.
     *
     */
    public function newAction(Request $request, $orderFixID)
    {
    	
 		//print "die";die;
		$this->get("services")->setVars('repairOrderFixConfirmationLog');
    	$session = new Session();
    			
        $em = $this->getDoctrine()->getManager();
		$user = $session->get('user_logged');
		
		
        $entity = new RepairOrderFixConfirmationLog();
		$entity->setRepairOrderFix($em->getRepository('SolucelAdminBundle:RepairOrderFix')->find(intval($orderFixID)));
		//var_dump($entity);die;
        $form   = $this->createCreateForm($entity);
		
		 
		
        return $this->render('SolucelAdminBundle:RepairOrderFixConfirmationLog:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

 

    /**
     * Finds and displays a RepairOrderFixConfirmationLog entity.
     *
     */
    public function showAction($entity)
    {
        $deleteForm = $this->createDeleteForm($entity);

        return $this->render('solucel_admin_repairorderfixconfirmationlog/show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }


	
    /**
     * Deletes a RepairOrderFixConfirmationLog entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
    	$this->get("services")->setVars('repairOrderFixConfirmationLog');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:RepairOrderFixConfirmationLog')->find($id);		
        $form = $this->createDeleteForm($entity);
        $form->handleRequest($request);

        //if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SolucelAdminBundle:RepairOrderFixConfirmationLog')->find($entity);


        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RepairOrderFixConfirmationLog entity.');
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
                    return $this->redirectToRoute('solucel_admin_repairorderfixconfirmationlog_index');
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
        return $this->redirectToRoute('solucel_admin_repairorderfixconfirmationlog_index');
    }

    /**
     * Creates a form to delete a RepairOrderFixConfirmationLog entity.
     *
     * @param RepairOrderFixConfirmationLog The RepairOrderFixConfirmationLog entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('solucel_admin_repairorderfixconfirmationlog_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
	
	
	
	
    /**
     * Creates a new RepairOrderFixConfirmationLog entity.
     *
     */
    public function createAction(Request $request)
    {
    	
		
		
 		//print "die";die;
		$this->get("services")->setVars('repairOrderFixConfirmationLog');
    	$session = new Session();
    			
        $em = $this->getDoctrine()->getManager();
		$user = $session->get('user_logged');
				
		
        $entity = new RepairOrderFixConfirmationLog();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
		/*print "<pre>";
		var_dump($form->getErrorsAsString());die;
		 * */
		 
        if ($form->isValid()) {
        	
			$formPost = $request->request->get("solucel_adminbundle_repairorderfixconfirmationlog");
        	$em = $this->getDoctrine()->getManager();
			
			$objOrderFix = $em->getRepository('SolucelAdminBundle:RepairOrderFix')->find(intval($_REQUEST["orderFixID"]));
			$orderID = $objOrderFix->getRepairOrder()->getId();
			$objOrder = $em->getRepository('SolucelAdminBundle:RepairOrder')->find($orderID);
			
			if(intval($formPost["clientConfirmation"]) == 1){//confirmó la reparación
				//orderfix->clientConfirmation = 1
				$confirmation = 1;
				
				
				//orderStatus en Repacion
				$status = $em->getRepository('SolucelAdminBundle:RepairStatus')->findByName("EN REPARACION");
				$objOrder->setRepairStatus($status[0]);
				 
				
			}
			else{
				//print "entra";die;	
				//denegó la reparación
				//orderfix->clientConfirmation = 0
				//orderStatus No Confirmada
				$confirmation = 0;
				
				//orderStatus en Repacion
				$status = $em->getRepository('SolucelAdminBundle:RepairStatus')->findByName("NO REPARADO/NO CONFIRMADO");
				$objOrder->setRepairStatus($status[0]);	
				$objOrder->setFinishedAt(new \DateTime("now"));
				
			}
			
			$objOrderFix->setClientRepairmentConfirmation($confirmation);
			
			//GUARDAR EN LOG DE STATUS DE ORDEN

			$objRepairOrderStatus = new RepairOrderStatus();
			$objRepairOrderStatus->setCreatedAtValue();
			$objRepairOrderStatus->setRepairStatus($status[0]);
			$objRepairOrderStatus->setRepairOrder($objOrder);
			
			$user = $em->getRepository('SolucelAdminBundle:User')->find($user->getId()); 			
			$objRepairOrderStatus->setCreatedBy($user);
			
			$em->persist($objRepairOrderStatus);
			$em->flush();
			
						
			//guardar orderfix y order
			$objOrderFix->setUpdatedAtValue();
            $em->persist($objOrderFix);
            $em->flush();
			$objOrder->setUpdatedAtValue();
            $em->persist($objOrder);
            $em->flush();
						
			$entity->setCreatedAtValue();
			$entity->setCreatedBy($user);
			$entity->setRepairOrderFix($objOrderFix);
			
            $em->persist($entity);
            $em->flush();


			$this->get('services')->flashSuccess($request);
            return $this->redirect($this->generateUrl('solucel_admin_repairorderfixconfirmationlog_index'));
			 
        }
		/*
		else{
			print "FORMULARIO NO VALIDO";DIE;
		}
		 * */

        return $this->render('SolucelAdminBundle:RepairOrderFixConfirmationLog:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a RepairOrderFixConfirmationLog entity.
     *
     * @param RepairOrderFixConfirmationLog $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm($entity)
    {
    	
		$this->get("services")->setVars('repairOrderFixConfirmationLog');
    	$session = new Session();
				
    	//$this->setVars();
        $form = $this->createForm(RepairOrderFixConfirmationLogType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_repairorderfixconfirmationlog_create'),
            'method' => 'POST',
            //'operator' => $session->get("user_operator"),            
            //'client' => $this->userLogged,
        ));



        return $form;
    }	
	

	
	
    /**
    * Creates a form to edit a RepairOrderFixConfirmationLog entity.
    *
    * @param RepairOrderFixConfirmationLog $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm($entity)
    {
    	
		$this->get("services")->setVars('repairOrderFixConfirmationLog');
    	$session = new Session();
				
		
        $form = $this->createForm(RepairOrderFixConfirmationLogType::class, $entity, array(
            'action' => $this->generateUrl('solucel_admin_repairorderfixconfirmationlog_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            //'operator' => $session->get("user_operator"),            
            //'client' => $this->userLogged,
        ));


        return $form;
    }
	
	



}


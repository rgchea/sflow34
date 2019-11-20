<?php

namespace Solucel\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Solucel\AdminBundle\Entity\Notification;
use Solucel\AdminBundle\Form\NotificationType;


use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Symfony\Component\HttpFoundation\JsonResponse;



use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


/**
 * Notification controller.
 *
 */
//todo: new ajax list
class NotificationController extends Controller
{



	/**
     * @var array
     */
    protected $columns = array(
        
        'u.username' => 'Usuario',
        'n.createdAt' => 'Fecha',
        'n.name' => 'Nombre',
        'n.description' => 'Descripcion',
        'n.id' => 'Acciones',
		 
    );

    /**
     * getQueryBuilder
     *
     * @param Request $request
     *
     * @return null
     */
    public function getQueryBuilder(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
		$userID = $this->get("services")->getUser()->getId();
		$qb = $em->getRepository('SolucelAdminBundle:Notification')->getNotificationByUser($userID);
        return $qb;
    }

    /**
     * dataAction
     *
     * @route("/ajax", name="notification_load_ajax")
     *
     * @param Request $request
     * @param null $dataFormatter
     *
     * @return JsonResponse
     */
    public function dataAction(Request $request, $dataFormatter = null)
    {
        $renderer = $this->get('templating');

        return parent::dataAction($request, function($data) use ($renderer) {
            $count   = 0;
            $results = array();

            foreach ($data as $row) {
                
                
				$results[$count][] = $row['username'];
				$results[$count][] = $renderer->render('SolucelAdminBundle:Notification:dateFormatter.html.twig', array('date' => $row['createdAt']));
				$results[$count][] = $row['name'];
				$results[$count][] = $renderer->render('SolucelAdminBundle:Notification:descFormatter.html.twig', array('description' => $row['description']));
				$results[$count][] = $renderer->render('SolucelAdminBundle:Notification:actionsFormatter.html.twig', array('id' => $row['id']));
                $count += 1;
            }

            return $results;
        });
    }	


    /**
     * Lists all Notification entities.
     *
     */
    public function indexAction()
    {
    	
    	//$this->get("services")->setVars('');
		$session = new Session();


        $em = $this->getDoctrine()->getManager();

        $session = new Session();

		$userID = $this->get("services")->getUser()->getId();
		$myNotifications = $em->getRepository('SolucelAdminBundle:Notification')->getNotificationsDetail($userID);
		//var_dump($myNotifications);

        return $this->render('SolucelAdminBundle:Notification:index.html.twig', array(
            'columns'       => $this->columns,
            'myNotifications' => $myNotifications,
        ));
    }
	
    /**
     * Creates a new Notification entity.
     *
     */
    public function createAction(Request $request)
    {
    	
		//var_dump($_REQUEST);
		
        $entity = new Notification();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
		/*print "<pre>";
        var_dump($_REQUEST);
		print "</pre>";
			exit();*/
        if ($form->isValid()) {
        	
			$entity->setCreatedAtValue();
			$entity->setEnabled(1);
			
			
			$intUser = intval($_REQUEST["wbc_administratorbundle_notification"]["fosUser"]);
			if($intUser != 0){
				$em = $this->getDoctrine()->getManager();
				$user = $em->getRepository('SolucelAdminBundle:User')->find($intUser);	
				$entity->setFosUser($user);
			}
			
			
			$entity->setCreatedBy($this->get("services")->getUser());
			$entity->setAlreadyRead(0);
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();


			$this->get('services')->flashSuccess($request);
            return $this->redirect($this->generateUrl('opera_shipper_notification'));
       }

      return $this->render('SolucelAdminBundle:Notification:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Notification entity.
     *
     * @param Notification $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Notification $entity)
    {
    	$this->setVars();
        $form = $this->createForm(NotificationType::class, $entity, array(
            'action' => $this->generateUrl('opera_shipper_notification_create'),
            'method' => 'POST',
            'shipper' => $this->userLogged,
        ));

        return $form;
    }

    /**
     * Displays a form to create a new Notification entity.
     *
     */
    public function newAction()
    {
    	$this->setVars();
        $entity = new Notification();
        $form   = $this->createCreateForm($entity);

        return $this->render('SolucelAdminBundle:Notification:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Notification entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:Notification')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Notification entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SolucelAdminBundle:Notification:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Notification entity.
     *
     */
    public function editAction($id)
    {
    	$this->setVars();
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:Notification')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Notification entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SolucelAdminBundle:Notification:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Notification entity.
    *
    * @param Notification $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Notification $entity)
    {
    	$this->setVars();
        $form = $this->createForm(NotificationType::class, $entity, array(
            'action' => $this->generateUrl('opera_shipper_notification_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'client' => $this->userLogged,
        ));



        return $form;
    }
    /**
     * Edits an existing Notification entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
    	//$this->setVars();
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SolucelAdminBundle:Notification')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Notification entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
        	$entity->setUpdatedBy($this->get("services")->getUser());
			$entity->setUpdatedAtValue();
            $em->flush();


			$this->get('services')->flashSuccess($request);
            return $this->redirect($this->generateUrl('opera_shipper_notification_edit', array('id' => $id)));
        }

        return $this->render('SolucelAdminBundle:Notification:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Notification entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        //if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SolucelAdminBundle:Notification')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Notification entity.');
            }


            //$em->remove($entity);
			$entity->setEnabled(0);
            $em->persist($entity);
                        
            $em->flush();
        //}

        $this->get('services')->flashSuccess($request);
        return $this->redirect($this->generateUrl('opera_shipper_notification'));
    }

    /**
     * Creates a form to delete a Notification entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('opera_shipper_notification_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
	
    public function staffAction(Request $request)
    {
    	
		$em = $this->getDoctrine()->getManager();
		$staff = $em->getRepository('SolucelAdminBundle:User')->findShipperStaffByDepartmentId(intval($_REQUEST["department_id"]));		
		
		$arrStaff = array();
		foreach ($staff as $user) {
			$arrStaff[$user->getFosUser()->getId()] = $user->getName()." ". $user->getLastName()."-". $user->getPosition();
		}		
    	
		return new JsonResponse($arrStaff);
		
		 
    }	
	
	public function getNotificationAction(Request $request){
        //$this->setVars();

        $em = $this->getDoctrine()->getManager();
		
		$shipperID = $this->get("services")->getUser()->getId();
        $entities = $em->getRepository("SolucelAdminBundle:Notification")->getShipperNotifications($shipperID);

        return $this->render('SolucelAdminBundle:Notification:getNotification.html.twig', array(
            'entities' => $entities,
        ));
		
	}	

	public function notificationCountAction(Request $request){
        //$this->setVars();
		//print "entra putos 2";die;
        $em = $this->getDoctrine()->getManager();
		
		$userID = $this->get("services")->getUser()->getId();
        $notificationsCount = $em->getRepository("SolucelAdminBundle:Notification")->getNotificationsCount($userID);
		print $notificationsCount; die;
		
	}
	
	public function readNotificationAction(Request $request ){
		
		$em = $this->getDoctrine()->getManager();
		
		$id = intval($_REQUEST["id"]);
		$value = intval($_REQUEST["value"]);
		
		$notification = $em->getRepository("SolucelAdminBundle:Notification")->find($id);
		$notification->setAlreadyRead($value);
		$notification->setUpdatedAtValue();
		$userID = $this->get("services")->getUser()->getId();
		$user = $em->getRepository("SolucelAdminBundle:User")->find($userID);
		$notification->setUpdatedBy($user);
		$em->persist($notification);
        $em->flush();
		die;
		//return $this->redirect($this->generateUrl('opera_client_notification'));
		
	}	

}

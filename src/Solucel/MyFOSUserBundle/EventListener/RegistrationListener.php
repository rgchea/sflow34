<?php

namespace Opera\MyFOSUserBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Symfony\Component\Routing\RequestContext;

/**
 * Listener responsible for adding the default user role at registration
 */
class RegistrationListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
        );
    }

    public function onRegistrationSuccess(FormEvent $event)
    {
    	$context = new RequestContext($_SERVER['REQUEST_URI']);
		//$currentUrl = $this->getRequest()->getUri();
		$url = $context->getBaseUrl();
		
		$arrUrl = explode("/", $url);
		
		$index = count($arrUrl)-1; 
		$userType = $arrUrl[intval($index)];
		
        /** @var $user \FOS\UserBundle\Model\UserInterface */
        $user = $event->getForm()->getData();
		//$userID = $user->getId();
		
		//var_dump($user);die;
		
		if($userType == "client"){
			$rolesArr = array('ROLE_CLIENT');
		}
		elseif ($userType == "shipper"){
			$rolesArr = array('ROLE_OPERATOR');
		}
		elseif ($userType == "agent"){
			$rolesArr = array('ROLE_AGENT');
		}		
		else{
			///ERROR
		}
		
		
        $user->setRoles($rolesArr);
		
    }
}
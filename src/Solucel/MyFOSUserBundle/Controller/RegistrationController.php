<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Solucel\MyFOSUserBundle\Controller;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;

use Solucel\AdminBundle\Entity\Client as Client;
#use Wbc\AdministratorBundle\Entity\Shipper as Shipper;



/**
 * Controller managing the registration
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 * @author Christophe Coevoet <stof@notk.org>
 */
class RegistrationController extends Controller
{
	
    /**
     * Tell the user to check his email provider
     */
    public function checkEmailAction()
    {
    	
        $email = $this->get('session')->get('fos_user_send_confirmation_email/email');
        $this->get('session')->remove('fos_user_send_confirmation_email/email');
        $user = $this->get('fos_user.user_manager')->findUserByEmail($email);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with email "%s" does not exist', $email));
        }
		
		$em = $this->getDoctrine()->getManager();		
		
		
        $roles = $user->getRoles();
		//var_dump($roles);
        
		foreach ($roles as $key => $value) {

	        if(($value == 'ROLE_CLIENT')) {
				$entity = new Client();
				$entity->setFosUser($user);
				$entity->setCode($user->getId());
				$entity->setStatus("Activo");
				$entity->setFiscalIdentification("");
				$entity->setCommercialName("");
				$entity->setLegalName("");
				$entity->setAddress($this->get('session')->get('register_address'));
				$this->get('session')->remove('register_address');
				$entity->setPhone($this->get('session')->get('register_phone'));
				$this->get('session')->remove('register_phone');
				$entity->setRanking(5);
				$entity->setMapState($em->getRepository('WbcAdministratorBundle:MapState')->find(intval($this->get('session')->get('register_state'))));
				$this->get('session')->remove('register_state');
				$entity->setMapTown($em->getRepository('WbcAdministratorBundle:MapTown')->find(intval($this->get('session')->get('register_town'))));
				$this->get('session')->remove('register_town');
				
				$entity->setCreatedAtValue();
				$entity->setUpdatedAtValue();
		
	        }
			elseif(($value == 'ROLE_OPERATOR') || ($value == 'ROLE_AGENT')) {
				
				$entity = new Shipper();
				$entity->setFosUser($user);
				$entity->setCommercialName("");
				$entity->setLegalName("");
				$entity->setAccountNumber("");
				$entity->setRanking(5);
				$entity->setPhone($this->get('session')->get('register_phone'));
				$this->get('session')->remove('register_phone');
				$entity->setRanking(5);
				$entity->setMapState($em->getRepository('WbcAdministratorBundle:MapState')->find(intval($this->get('session')->get('register_state'))));
				$this->get('session')->remove('register_state');
				$entity->setMapTown($em->getRepository('WbcAdministratorBundle:MapTown')->find(intval($this->get('session')->get('register_town'))));
				$this->get('session')->remove('register_town');
				
				$entity->setCreatedAtValue();
				$entity->setUpdatedAtValue();
				
				$name = "Completar perfil";
				$description = "Debes de completar tu perfil para poder usar la plataforma, de lo contrario tu usuario no puede ser encontrado por los clientes y no tendras oportunidades de Transporte. Ir al menú perfil y luego en la pestaña perfil.";
								
				$this->get("services")->systemNotification($user, $name, $description, null);
	        }
			
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();		
			
			break;			
			
		}

		

        return $this->render('SolucelMyFOSUserBundle:Registration:checkEmail.html.twig', array(
            'user' => $user,
        ));
    }

    /**
     * Receive the confirmation token from user email provider, login the user
     */
    public function confirmAction(Request $request, $token)
    {
		
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');

        $user = $userManager->findUserByConfirmationToken($token);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with confirmation token "%s" does not exist', $token));
        }

        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');
		
		
		
        $user->setConfirmationToken(null);
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_CONFIRM, $event);

        $userManager->updateUser($user);

        if (null === $response = $event->getResponse()) {
            $url = $this->generateUrl('fos_user_registration_confirmed');
            $response = new RedirectResponse($url);
        }

        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_CONFIRMED, new FilterUserResponseEvent($user, $request, $response));

        return $response;
    }

    /**
     * Tell the user his account is now confirmed
     */
    public function confirmedAction()
    {
    	
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('Usted no tiene acceso a esta sección.');
        }

        return $this->render('SolucelMyFOSUserBundle:Registration:confirmed.html.twig', array(
            'user' => $user,
        ));
    }
}

<?php
namespace Solucel\AdminBundle\Services;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Security\Core\Security;

use Symfony\Component\HttpFoundation\JsonResponse;

class Services extends Controller
{

    private $em;
    protected $container;
    private $security;
    private $translator;

    public function __construct(EntityManagerInterface $entityManager, ContainerInterface $container, Security $security){
        $this->em = $entityManager;
        $this->container = $container;
        $this->security = $security;
        $this->translator = $this->get('translator');
    }

    public function getRandomCode($length = 8, $entity = 'User', $min = false){

        if($min){
            $chars = "_ABCDEFGHIJKLMNO-PQRSTUVWX_Yab_cdefghijklmnnopqrs-tu-vwxyz011234567_89";
        }else{
            $chars = "ABCDEFGHIJKLMNOPQRSTUVWXY023456789";
        }

        srand((double)microtime()*1000000);
        $i = 0;
        $pass = '' ;

        while ($i <= $length) {
            $num = rand() % 33;
            $tmp = substr($chars, $num, 1);
            $pass = $pass . $tmp;
            $i++;
        }

        if($entity == 'User'){
            $code_field = 'secret_token';
        }else{
            $code_field = 'code';
        }

        $doesCodeExistsBefore = $this->em->getRepository('SolucelAdminBundle:' . $entity)
            ->findOneBy(array($code_field => $pass));

        if($doesCodeExistsBefore){
            $pass = $this->getRandomCode($length);
        }

        return $pass;
    }



    public function getUser(){

        //return $this->container->get('security.context')->getToken()->getUser();
        return $this->security->getUser();
    }



	//public function finishedOrderMail($to, $orderID, $brand, $model){
	public function finishedOrderMail($to, $orderID, $entryDate){

	    $mailer = $this->get('mailer');
	    $message = $mailer->createMessage()
	        ->setSubject("Dispositivo listo para entrega")
	        //->setTo('rchea@operalogistica.com');
	        ->setTo($to);
			$message->setFrom("info@gruposolucel.com");

		$body = "Estimado cliente, le informamos que la unidad ya se encuentra lista para entrega en nuestro centro de atención.";
		$body .= " Orden:{$orderID}";
		$body .= " Fecha Ingreso:{$entryDate}";

		//$body = "Su dispositivo ".$brand. " ".$model." ha sido diagnosticado/reparado puede pasar a recogerlo. El número de orden es #".$orderID .". Gracias por preferirnos.";

        $message->setBody(
            $this->container->get('templating')->render('SolucelAdminBundle:Default:email.html.twig', array('content' => $body)),
            'text/html'
        );

	    return $mailer->send($message);

	}


	public function generalTemplateMail($subject, $to, $body, $from = null){

	    $mailer = $this->get('mailer');
	    $message = $mailer->createMessage()
	        ->setSubject($subject)
	        //->setTo('rchea@operalogistica.com');
	        ->setTo($to);

			if($from != null){
				$message->setFrom($from);
			}
			else{
				$message->setFrom("info@pizotesoftdev.com");
			}


        $message->setBody(
            $this->container->get('templating')->render('SolucelAdminBundle:Default:email.html.twig', array('content' => $body)),
            'text/html'
        );

	    return $mailer->send($message);
	}
	
	
	public function motorolaMail($to, $directory, $filename){

	    $mailer = $this->get('mailer');
		
		//foreach ($to as $mail) {
			
		    $message = $mailer->createMessage()
		        ->setSubject("Claims_File_Data_Feed-Solucel-GT")
		        ->setTo($to)
				->setFrom("info@gruposolucel.com")
				->attach(\Swift_Attachment::fromPath($directory.$filename))
				;
	
			$body = "Motorola Datafeed.";
	
	        $message->setBody(
	            $this->container->get('templating')->render('SolucelAdminBundle:Default:email.html.twig', array('content' => $body)),
	            'text/html'
	        );
	
		    $send = $mailer->send($message);			
		//}
		

				
		return $send;		

	}	


	public function checkUsername($username, $id){

		$em = $this->getDoctrine()->getManager();
		$check = $em->getRepository('SolucelAdminBundle:User')->findByUsername($username);

		//var_dump($check);die;


		$checkId = $em->getRepository('SolucelAdminBundle:User')->findById($id);


		if($id == 0){
			if(count($check)){
				return TRUE;
			}
			else{
				return FALSE;
			}
		}
		else{
			if(empty($check) || empty($checkId)){
				return FALSE;
			}
			else{
				if($check[0]->getId() == $checkId[0]->getId()){
					return FALSE;
				}
				else{
					return TRUE;
				}

			}


		}

	}

	public function checkEmail($email, $id){

		$em = $this->getDoctrine()->getManager();
		$check = $em->getRepository('SolucelAdminBundle:User')->findByEmail($email);


		$checkId = $em->getRepository('SolucelAdminBundle:User')->findById($id);


		if($id == 0){
			//new
			if(count($check)){
				return TRUE;
			}
			else{
				return FALSE;
			}
		}
		else{
			//edit

			/*
			print "entra";
			var_dump($check);
			var_dump($checkId[0]);
			die;
			 *
			 */

			if(count($check) &&  count($checkId)){
				if($check[0]->getId() == $checkId[0]->getId()){
					return FALSE;
				}
				else{
					return TRUE;
				}

			}
			else{
				return FALSE;
			}

		}

	}


	public function checkExistence($username, $email, $id){

		$checkUser = $this->checkUsername($username, $id);
		$checkEmail = $this->checkEmail($email, $id);

		$warning = "";
		if($checkUser){

			$warning = "El Usuario sugerido ya fue tomado!";
		}
		elseif ($checkEmail) {
			$warning = "El Correo sugerido ya fue registrado por otro usuario!";

		}

		return $warning;
	}


	public function flashWarning($request){

		$request->getSession()->getFlashBag()->add('warning','Tus cambios no fueron guardados, intenta de nuevo!');
	}

    public function flashWarningCustom($request, $msg){

        $request->getSession()->getFlashBag()->add('warning',$msg);
    }


	public function flashSuccess($request){

		$request->getSession()->getFlashBag()->add('success','Tus cambios fueron guardados!');
	}

	public function flashWarningForeignKey($request){
		//type success, warning, danger, info
		$request->getSession()->getFlashBag()->add('warning','No se puede eliminar, existen registros relacionados');
	}

	public function userHasAccess($session){


		$item = $session->get('item');
		//var_dump($item);
		$userAccess = $session->get('user_access');

		if($userAccess != null){


			$object = (object) $userAccess;

			//print "<pre>";
			//var_dump($object);die;
			/*
			foreach ($object as $key => $value) {

			}
			 **/
			foreach ($object as $key => $value) {
				if($value["moduleType"] == "menu"){
					/*
					print "entra";
					print_r($value);
					 *
					 */
					foreach ($value as $llave => $valor) {
						if(is_array($valor) ){
							if($item == $valor["systemName"]){
								//print "ENTRA PUTO";DIE;
								return true;
							}
						}
						//print_r($valor);

					}
				}
				else{
					if($item == $value["systemName"]){
						return true;
					}
				}
			}
			//die;


		}


		return false;
	}


	public function getUserAccess($session){

		$role = "ROLE_USER";
		//$session->set("user_access", "all");
		$clientAccess = array();

		$user = $this->getUser();
		$role = $user->getRole();
		//var_dump($client->getRoles());die;

		$userAccess = $this->em->getRepository('SolucelAdminBundle:ModuleAccess')->getUserAccessByRole($role->getId());
		/*print "<pre>";
		var_dump($userAccess);die;*/
		$session->set("user_access", $userAccess);
		$session->set("user_role", $role);
		//get permissions

	}




	public function systemNotification($to, $name, $description, $type = null){

		//print "entra";die;
		//fosUserId
		//createdBy
		//NotificationType ->Recordatorio
		//name
		//enabled -> 1
		//registrationDate
		//expirationDate
		//description
		//createdAt
		//alreadRead -> 0
		$notification = $this->em->getRepository('SolucelAdminBundle:Notification')->systemNotification($to, $name, $description, $type);

	}



	public function setVars($item){

		$session = new Session();

		$em = $this->em;
        $session->set('item', $item);

		$auth_checker = $this->get('security.authorization_checker');
		$auth = $auth_checker->isGranted('ROLE_USER');

		if(!$auth){
			throw new AccessDeniedException();//el usuario está loggeado
		}


		$user = $this->getUser();
		$session->set('user_logged', $user);
		//var_dump($user);die;

		//var_dump($user);die;

		if($user->getServiceCenter() == NULL){
			$session->set('user_service_center', 0);
		}
		else{
			$session->set('user_service_center', $user->getServiceCenter()->getId());
		}

		if($user->getDeviceBrand() == NULL){
			$session->set('user_device_brand', 0);
		}
		else{
			$session->set('user_device_brand', $user->getDeviceBrand()->getId());
		}


		if($user->getOperator() == NULL){
			$session->set('user_operator', 0);
		}
		else{
			$session->set('user_operator', $user->getOperator()->getId());
		}



		$this->getUserAccess($session);
		$access = $this->userHasAccess($session);

		if(!$access){
			throw new AccessDeniedException();
		}
		//DIE;
        $session->set('userLogged', $em->getRepository('SolucelAdminBundle:User')->find($user));

    }


	function quitar_tildes($cadena) {
		$no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
		$permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
		$texto = str_replace($no_permitidas, $permitidas ,$cadena);
		return $texto;
	}


    /**
    * Returns an string clean of UTF8 characters. It will convert them to a similar ASCII character
    * www.unexpectedit.com
    */
	function cleanString($text) {
	    // 1) convert á ô => a o
	    $text = preg_replace("/[áàâãªä]/u","a",$text);
	    $text = preg_replace("/[ÁÀÂÃÄ]/u","A",$text);
	    $text = preg_replace("/[ÍÌÎÏ]/u","I",$text);
	    $text = preg_replace("/[íìîï]/u","i",$text);
	    $text = preg_replace("/[éèêë]/u","e",$text);
	    $text = preg_replace("/[ÉÈÊË]/u","E",$text);
	    $text = preg_replace("/[óòôõºö]/u","o",$text);
	    $text = preg_replace("/[ÓÒÔÕÖ]/u","O",$text);
	    $text = preg_replace("/[úùûü]/u","u",$text);
	    $text = preg_replace("/[ÚÙÛÜ]/u","U",$text);
	    $text = preg_replace("/[’‘‹›‚]/u","'",$text);
	    $text = preg_replace("/[“”«»„]/u",'"',$text);
	    $text = str_replace("–","-",$text);
	    $text = str_replace(" "," ",$text);
	    $text = str_replace("ç","c",$text);
	    $text = str_replace("Ç","C",$text);
	    $text = str_replace("ñ","n",$text);
	    $text = str_replace("Ñ","N",$text);

	    //2) Translation CP1252. &ndash; => -
	    $trans = get_html_translation_table(HTML_ENTITIES);
	    $trans[chr(130)] = '&sbquo;';    // Single Low-9 Quotation Mark
	    $trans[chr(131)] = '&fnof;';    // Latin Small Letter F With Hook
	    $trans[chr(132)] = '&bdquo;';    // Double Low-9 Quotation Mark
	    $trans[chr(133)] = '&hellip;';    // Horizontal Ellipsis
	    $trans[chr(134)] = '&dagger;';    // Dagger
	    $trans[chr(135)] = '&Dagger;';    // Double Dagger
	    $trans[chr(136)] = '&circ;';    // Modifier Letter Circumflex Accent
	    $trans[chr(137)] = '&permil;';    // Per Mille Sign
	    $trans[chr(138)] = '&Scaron;';    // Latin Capital Letter S With Caron
	    $trans[chr(139)] = '&lsaquo;';    // Single Left-Pointing Angle Quotation Mark
	    $trans[chr(140)] = '&OElig;';    // Latin Capital Ligature OE
	    $trans[chr(145)] = '&lsquo;';    // Left Single Quotation Mark
	    $trans[chr(146)] = '&rsquo;';    // Right Single Quotation Mark
	    $trans[chr(147)] = '&ldquo;';    // Left Double Quotation Mark
	    $trans[chr(148)] = '&rdquo;';    // Right Double Quotation Mark
	    $trans[chr(149)] = '&bull;';    // Bullet
	    $trans[chr(150)] = '&ndash;';    // En Dash
	    $trans[chr(151)] = '&mdash;';    // Em Dash
	    $trans[chr(152)] = '&tilde;';    // Small Tilde
	    $trans[chr(153)] = '&trade;';    // Trade Mark Sign
	    $trans[chr(154)] = '&scaron;';    // Latin Small Letter S With Caron
	    $trans[chr(155)] = '&rsaquo;';    // Single Right-Pointing Angle Quotation Mark
	    $trans[chr(156)] = '&oelig;';    // Latin Small Ligature OE
	    $trans[chr(159)] = '&Yuml;';    // Latin Capital Letter Y With Diaeresis
	    $trans['euro'] = '&euro;';    // euro currency symbol
	    ksort($trans);

	    foreach ($trans as $k => $v) {
	        $text = str_replace($v, $k, $text);
	    }

	    // 3) remove <p>, <br/> ...
	    $text = strip_tags($text);

	    // 4) &amp; => & &quot; => '
	    $text = html_entity_decode($text);

	    // 5) remove Windows-1252 symbols like "TradeMark", "Euro"...
	    $text = preg_replace('/[^(\x20-\x7F)]*/','', $text);

	    $targets=array('\r\n','\n','\r','\t');
	    $results=array(" "," "," ","");
	    $text = str_replace($targets,$results,$text);

	    //XML compatible
	    /*
	    $text = str_replace("&", "and", $text);
	    $text = str_replace("<", ".", $text);
	    $text = str_replace(">", ".", $text);
	    $text = str_replace("\\", "-", $text);
	    $text = str_replace("/", "-", $text);
	    */
	     //cleanString(utf8_encode($val));
	    return ($text);
	}

    /**
     * @param $value
     * @return mixed
     */
    function escapeJsonString($value) { # list from www.json.org: (\b backspace, \f formfeed)
        $escapers = array("\\", "/", "\"", "\n", "\r", "\t", "\x08", "\x0c");
        $replacements = array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t", "\\f", "\\b");
        $result = str_replace($escapers, $replacements, $value);
        return $result;
    }


    public function serviceDataTable($request, $repository, $results, $myItems){


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

        // Returned objects are of type Town
        $objects = $results["results"];
        // Get total number of objects
        $total_objects_count = $this->getQueryCount($repository);

        // Get total number of results
        //$selected_objects_count = count($objects);
        // Get total number of filtered data
        $filtered_objects_count = $results["countResult"];

        // Construct response
        $response = '{
            "draw": '.$draw.',
            "recordsTotal": '.$total_objects_count.',
            "recordsFiltered": '.$filtered_objects_count.',
            "data": [';

        $response .= $myItems;


        $response .= ']}';

        // Send all this stuff back to DataTables
        $returnResponse = new JsonResponse();
        $returnResponse->setJson($response);

        return $returnResponse;

    }


    public function getQueryCount($repository){

        //$repository = $this->getDoctrine()->getRepository(Product::class);

        return $repository
            ->createQueryBuilder('object')
            ->select("count(object.id)")
            //->where("object.enabled = 1")
            ->getQuery()
            ->getSingleScalarResult();


    }




}

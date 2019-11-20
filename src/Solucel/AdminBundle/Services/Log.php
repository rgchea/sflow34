<?php
namespace Solucel\AdminBundle\Services;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManager;

class Log extends Controller
{
    
    private $em;
    
    public function add($text, $user, $action = 'none'){
        $log = new \Solucel\AdminBundle\Entity\RepairLog();
        $log->setAction($action);
        $log->setFosUser($user);
        $log->setText($text);
        $log->setCreatedAt(new \DateTime());
        
        $this->em->persist($log);
        $this->em->flush();
    }
    
    public function __construct(EntityManager $entityManager){
        $this->em = $entityManager;
    }
}
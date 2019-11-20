<?php
namespace Solucel\AdminBundle\Command;
 
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


use Solucel\AdminBundle\Entity\RepairStatus;
use Solucel\AdminBundle\Entity\RepairOrderStatus;

 
class MyFinishedCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('solucel:finished')
            ->setDescription('Command description')
            //->addArgument('my_argument', InputArgument::OPTIONAL, 'Argument description')
			;
    }
 
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        // Do whatever
        
        $entities = $em->getRepository('SolucelAdminBundle:RepairOrder')->getUnfinishedOrders();
		
		foreach ($entities as $entity) {
			
			$orderID = $entity["id"];
			
			 $repair_status = $em->getRepository('SolucelAdminBundle:RepairStatus')->findByName("ENTREGADO");
			 if($repair_status){
			 	$repair_status =  $repair_status[0];
			 }
			 
			 $objOrderRepairStatus = new RepairOrderStatus();
			 
			 $objOrder = $em->getRepository('SolucelAdminBundle:RepairOrder')->find($orderID);
			 $objOrderRepairStatus->setRepairOrder($objOrder);
			 
			 $objUser = $em->getRepository('SolucelAdminBundle:User')->find(1);
			 $objOrderRepairStatus->setCreatedBy($objUser);
			 
			 $objOrderRepairStatus->setRepairStatus($repair_status);
			 $objOrderRepairStatus->setCreatedAtValue();
			 
			 $em->persist($objOrderRepairStatus);
	               
			 
			 $objOrder->setRepairStatus($repair_status);
			 $em->persist($objOrder);
			 
			
		}

		$em->flush();
		
		
		
        /*
        $output->writeln('Hello World');
        $em->flush();
		 * 
		 */
		
						 
			 	 
		 
    }
}
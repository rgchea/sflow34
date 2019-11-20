<?php
namespace Solucel\AdminBundle\Command;
 
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
 
class MyCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('solucel:motorola')
            ->setDescription('Command description')
            //->addArgument('my_argument', InputArgument::OPTIONAL, 'Argument description')
			;
    }
 
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        // Do whatever
        
        
        /*
        $output->writeln('Hello World');
        $em->flush();
		 * 
		 */
		 
        
		set_time_limit(2000000);
    	$now =  date("Ymdhis");
    	
		$txtData = array();			
		 
 		$directory = __DIR__.'/../../../../web/uploads/motorolaDataFeed/';
 		//$directory = 'C:/xampp/htdocs/sflow/web/uploads/motorolaDataFeed/';
		$filename = "Claims_File_Data_Feed-Solucel-GT.txt";
		

			$fp = fopen($directory.$filename, 'w');
			
			$arrHeader =  array();
			$arrHeader[]  = "Accident date";//NULL
			$arrHeader[]  = "Action Code";
			$arrHeader[]  = "Action Reason Code";
			$arrHeader[]  = "AWB In";//NULL
			$arrHeader[]  = "AWB Out";//NULL
			$arrHeader[]  = "Board Serial Number In";//NULL
			$arrHeader[]  = "Board Serial Number Out";//NULL
			$arrHeader[]  = "Claim ID";
			$arrHeader[]  = "ClaimLine ID";//NULL
			$arrHeader[]  = "Complaint Date";//NULL
			$arrHeader[]  = "Courier In";//NULL
			$arrHeader[]  = "Courier Out";//NULL
			$arrHeader[]  = "Customer Complaint Code - Primary";
			$arrHeader[]  = "Customer complaint code secondary";//NULL
			$arrHeader[]  = "Date In";
			$arrHeader[]  = "Date Out";
			$arrHeader[]  = "Delivery Date";//NULL
			$arrHeader[]  = "Delivery Number";//NULL
			$arrHeader[]  = "Document type";//NULL
			$arrHeader[]  = "Enduser country";
			$arrHeader[]  = "Enduser e-mail";
			$arrHeader[]  = "Enduser ID";
			$arrHeader[]  = "Enduser name";
			$arrHeader[]  = "Enduser phone number";
			$arrHeader[]  = "Enduser street";//NULL
			$arrHeader[]  = "Enduser town";
			$arrHeader[]  = "Enduser zip code";
			$arrHeader[]  = "Escalated Repair Type";//NULL
			$arrHeader[]  = "Escalated RSP";//NULL
			$arrHeader[]  = "Fault Code";
			$arrHeader[]  = "Field bulletin number";//NULL
			$arrHeader[]  = "IMEI 2 In";
			$arrHeader[]  = "IMEI 2 Out";
			$arrHeader[]  = "IMEI Number In";
			$arrHeader[]  = "IMEI Number Out";
			$arrHeader[]  = "Inbound Shipment Type";//NULL
			$arrHeader[]  = "Insurance start date";//NULL
			$arrHeader[]  = "Item code out";//NULL
			$arrHeader[]  = "Job Creation Date";//NULL
			$arrHeader[]  = "Mandatory";//NULL
			$arrHeader[]  = "Manufacture date";
			$arrHeader[]  = "Material Missing";//NULL
			$arrHeader[]  = "Material Number";
			$arrHeader[]  = "Material serial number";//NULL
			$arrHeader[]  = "OEM";
			$arrHeader[]  = "Outbound Shipment Type";//NULL
			$arrHeader[]  = "Payer";
			$arrHeader[]  = "Pickup Arranged Date";//NULL
			$arrHeader[]  = "Pickup Date";//NULL
			$arrHeader[]  = "POP date";
			$arrHeader[]  = "POP supplier";//NULL
			$arrHeader[]  = "Problem Found Code";
			$arrHeader[]  = "Problem found code secondary";//NULL
			$arrHeader[]  = "Product Code In";
			$arrHeader[]  = "Product Code Out";
			$arrHeader[]  = "Product Type";
			$arrHeader[]  = "Product version in";//NULL
			$arrHeader[]  = "Product version out";//NULL
			$arrHeader[]  = "Project";
			$arrHeader[]  = "Provider/Carrier";//NULL
			$arrHeader[]  = "QA Timepstamp";//NULL
			$arrHeader[]  = "Quantity Exchanged";//NULL
			$arrHeader[]  = "Quantity Missing";//NULL
			$arrHeader[]  = "Quantity Replaced";//NULL
			$arrHeader[]  = "Quotation End Date";//NULL
			$arrHeader[]  = "Quotation Start Date";//NULL
			$arrHeader[]  = "Quotation Status Code";//NULL
			$arrHeader[]  = "Reference designator number";//NULL
			$arrHeader[]  = "Remarks";//NULL
			$arrHeader[]  = "Repair Service Partner ID";//NULL
			$arrHeader[]  = "Repair Status Code";
			$arrHeader[]  = "Repair Timestamp";
			$arrHeader[]  = "Report date";//NULL
			$arrHeader[]  = "Return Date";//NULL
			$arrHeader[]  = "RMA Number";//NULL
			$arrHeader[]  = "Root Cause";//NULL
			$arrHeader[]  = "Second Status";//NULL
			$arrHeader[]  = "Serial Number In";
			$arrHeader[]  = "Serial Number Out";
			$arrHeader[]  = "Shipped From";//NULL
			$arrHeader[]  = "Shipped To";//NULL
			$arrHeader[]  = "Shop ID";
			$arrHeader[]  = "Shop In Date";//NULL
			$arrHeader[]  = "Shop Out Date";//NULL
			$arrHeader[]  = "Software In";//NULL
			$arrHeader[]  = "Software Out";
			$arrHeader[]  = "Solution Awaited Code";//NULL
			$arrHeader[]  = "Special project number";//NULL
			$arrHeader[]  = "Support Partner Ticket ID";//NULL
			$arrHeader[]  = "Technician ID";
			$arrHeader[]  = "Transaction Code";
			$arrHeader[]  = "Warranty Flag";
			$arrHeader[]  = "Warranty number";//NULL
			
					
			foreach ($arrHeader as $key => $value) {
				
				if($value == "Warranty number"){
					fputs($fp, $value);
				}
				else{
					fputs($fp, $value."\t");	
				}
				
					
			}
			fputs($fp, "\r\n");
			//$output = "Accident date\t Action Code\r\n";
			//$output .= "";
			
			
			$arrBody =  array();
			$arrBody["accident_date"] = NULL;
			$arrBody["action_code"] = NULL;//QUERY
			$arrBody["action_reason_code"]= NULL;//QUERY
			$arrBody["awb_in"]  = NULL;
			$arrBody["awb_out"]  = NULL;
			$arrBody["board_serial_number_in"]  = NULL;
			$arrBody["board_serial_number_out"]  = NULL;
			$arrBody["claim_id"] = NULL;//QUERY
			$arrBody["claimline_id"] = NULL;
			$arrBody["complaint_date"]  = NULL;
			$arrBody["courier_in"]  = NULL;
			$arrBody["courier_out"]  = NULL;
			$arrBody["customer_complaint_code_primary"] =  NULL;//QUERY
			$arrBody["customer_complaint_code_secondary"]  = NULL;
			$arrBody["date_in"] = "iso8601";//QUERY
			$arrBody["date_out"] = "iso8601";//QUERY
			$arrBody["delivery_date"] = NULL;
			$arrBody["delivery_number"]  = NULL;
			$arrBody["document_type"]  = NULL;
			$arrBody["enduser_country"] = NULL;//QUERY
			$arrBody["enduser_email"] = NULL;//QUERY
			$arrBody["enduser_id"] = NULL;//QUERY
			$arrBody["enduser_name"] = NULL;//QUERY
			$arrBody["enduser_phonenumber"] = NULL;//QUERY
			$arrBody["enduser_street"] = NULL;
			$arrBody["enduser_town"] = NULL;//QUERY
			$arrBody["enduser_zipcode"] = NULL;//QUERY
			$arrBody["escalated_repair_type"]  = NULL;
			$arrBody["escalated_rsp"]  = NULL;
			$arrBody["fault_code"] = NULL;//QUERY
			$arrBody["field_bulletin_number"]  = NULL;
			$arrBody["imei2_in"] = NULL;//QUERY
			$arrBody["imei2_out"] = NULL;//QUERY
			$arrBody["imei_number_in"] = NULL;//QUERY
			$arrBody["imei_number_out"] = NULL;//QUERY
			$arrBody["inbound_shipment_type"] = NULL;
			$arrBody["insurance_start_date"]  = NULL;
			$arrBody["item_code_out"]  = NULL;
			$arrBody["job_creation_date"]  =  NULL;
			$arrBody["mandatory"]  = NULL;
			$arrBody["manufacture_date"] = "iso8601";//QUERY
			$arrBody["material_missing"] = NULL;
			$arrBody["material_number"] = NULL;//QUERY
			$arrBody["material_serial_number"] = NULL;
			$arrBody["oem"] = NULL;//QUERY
			$arrBody["outbound_shipment_type"]  = NULL;
			$arrBody["payer"] = NULL;//QUERY
			$arrBody["pickup_arranged_date"]  = NULL;
			$arrBody["pickup_date"]  =  NULL;
			$arrBody["pop_date"] = "iso8601";//QUERY
			$arrBody["pop_supplier"]  = NULL;
			$arrBody["problem_found_code"] = NULL;//QUERY
			$arrBody["problem_found_code_secondary"]  = NULL;
			$arrBody["product_code_in"] = NULL;//QUERY
			$arrBody["product_code_out"] = NULL;//QUERY
			$arrBody["product_type"] = NULL;//QUERY
			$arrBody["product_version_in"]  = NULL;
			$arrBody["product_version_out"]  = NULL;
			$arrBody["project"] = NULL;//QUERY
			$arrBody["provider_carrier"]  = NULL;
			$arrBody["qa_timestamp"]  = NULL;
			$arrBody["quantity_exchanged"]  = NULL;
			$arrBody["quantity_missing"]  = NULL;
			$arrBody["quantity_replaced"]  = NULL;//QUERY
			$arrBody["quotation_end_date"]  = NULL;
			$arrBody["qotation_start_date"]  = NULL;
			$arrBody["quotation_status_code"]  = NULL;
			$arrBody["reference_designator_number"]  = NULL;
			$arrBody["remarks"]  = NULL;
			$arrBody["repair_service_partner_id"] = NULL;//QUERY
			$arrBody["repair_status_code"] = NULL;//QUERY
			$arrBody["repair_timestamp"] = "iso8601";//QUERY
			$arrBody["report_date"] = "iso8601";//QUERY
			$arrBody["return_date"]  = NULL;
			$arrBody["rma_number"]  = NULL;
			$arrBody["root_cause"]  = NULL;
			$arrBody["second_status"]  = NULL;
			$arrBody["serial_number_in"] = NULL;//QUERY
			$arrBody["serial_number_out"] = NULL;//QUERY
			$arrBody["shipped_from"]  = NULL;
			$arrBody["shipped_to"]  = NULL;
			$arrBody["shop_id"] = NULL;//QUERY
			$arrBody["shop_in_date"]  = NULL;
			$arrBody["shop_out_date"] = NULL;
			$arrBody["software_in"]  = NULL;
			$arrBody["software_out"] = NULL;//QUERY
			$arrBody["solution_awaited_code"]  = NULL;
			$arrBody["special_project_number"]  = NULL;
			$arrBody["support_partner_ticket_id"]  = NULL;
			$arrBody["technician_id"] = NULL;
			$arrBody["transaction_code"] = NULL;//QUERY
			$arrBody["warranty_flag"] = NULL;//QUERY
			$arrBody["warranty_number"] = NULL;		
			
			$entities = $em->getRepository('SolucelAdminBundle:RepairOrder')->getMotoDataFeed();
			
			foreach ($entities as $register) {
												
				foreach ($arrBody as $key => $value) {
	
					if( isset($register[$key]) ){
						
						if($value == "iso8601"){
							//fputs($fp, date_format(date_create(''.$register[$key]), 'c')."\t");
							fputs($fp, ''.$register[$key]." -06:00"."\t");
						}
						else{
							$data = $register[$key];
							if($data == NULL){
								$data = "NULL";
							}
							
							if($key == "warranty_number"){
								fputs($fp, $data);	
							}
							else{
								fputs($fp, $data."\t");
							}
							
							
								
						}					
						
					}
					else{
						
						if($key == "warranty_number"){
							fputs($fp, "NULL");
						}
						else{
							fputs($fp, "NULL\t");
						}
						
					}
				}	
					
				fputs($fp, "\r\n");
			}
			
			
			fclose($fp);	
			
			$to = array("datacollection@b2x.com", "Giovanni.Piedrasanta@b2x.com", "gnoriega@gruposolucel.com", "cheametal@gmail.com"); 
			//SEND FILE 
			$this->getContainer()->get("services")->motorolaMail($to, $directory,  $filename);
						 
			 	 
		 
    }
}
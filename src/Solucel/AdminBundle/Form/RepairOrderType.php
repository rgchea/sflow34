<?php

namespace Solucel\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RepairOrderType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

			//->add('operator', null, array('label'=>"Operador", 'required'=>true))
			->add('guideIn', null, array('label'=>"Guía Entrada", 'required'=>false))
			->add('guideOut', null, array('label'=>"Guía Salida", 'required'=>false))
			->add('ticketNumber', null, array('label'=>"Boleta No", 'required'=>true))
			->add('agency', null, array('label'=>"Agencia", 'required'=>true))
			->add('payer', null, array('label'=>"Payer", 'required'=>false))
			->add('phoneNumber', null, array('label'=>"Número Asociado", 'required'=>true))
			
			->add('warrantyFlag', null, array('label'=>"WarrantyFlag", 'required'=>false))
			/*						
			->add('createdAt', null ,array('data' => new \DateTime(), 'label'=>"Fecha Ingreso",'required'=>true,
				'attr' => array('class' => 'createdAt',  'readonly' => 'readonly'),
            	'widget' => 'single_text',
            	'format' => 'y-MM-dd h:m:s',
            	//'time_widget' => 'text'
            	//'with_minutes' => true
				))
			 * */
			->add('entryDate', null ,array('data' => new \DateTime(), 'label'=>"Fecha Ingreso",'required'=>true,
				'attr' => array('class' => 'createdAt',  'readonly' => 'readonly'),
            	'widget' => 'single_text',
            	'format' => 'dd/MM/y',
            	//'format' => 'y-MM-dd',
            	//'time_widget' => 'text'
            	//'with_minutes' => true
				))
				
			->add('estimatedDeliveryDate', null ,array('data' => new \DateTime(), 'label'=>"Fecha Estimada de Entrega",'required'=>true,
				'attr' => array('class' => 'estimatedDeliveryDate',  'readonly' => 'readonly'),
            	'widget' => 'single_text',
            	'format' => 'dd/MM/y',
            	//'format' => 'y-MM-dd',
				))
			->add('serviceCenter', null, array("label" => "Centro de Servicio", "required" => true))
			//DEVICE START
			->add('devicePlan', ChoiceType::class, array('label'=>"Plan", 'required'=>true, 
					'choices' => array('PostPago' => 'Postpago', 'Prepago' => 'Prepago')))
			->add('deviceType', null, array('label'=>"Tipo de dispositivo", 'required'=>true))
			->add('deviceBrand', null, array('label'=>"Marca", 'required'=>true))
			->add('deviceModel', null, array('label'=>"Modelo", 'required'=>true))
			->add('deviceColor', null, array('label'=>"Color", 'required'=>true))
			->add('deviceImei', null, array('label'=>"IMEI", 'required'=>true))
			->add('deviceImei2', null, array('label'=>"IMEI2", 'required'=>false))
			->add('deviceBorrowedImei', null, array('label'=>"IMEI de dispositivo de préstamo", 'required'=>false))
			->add('deviceXcvr', null, array('label'=>"XCVR", 'required'=>true))
			->add('deviceMsn', null, array('label'=>"MSN", 'required'=>true))
			->add('deviceCodeFab', null, array('label'=>"Código de fabricación", 'required'=>true))
			->add('deviceProblem', null, array('label'=>"Descripción del problema", 'required'=>true))
			->add('deviceObservation', null, array('label'=>"Observaciones", 'required'=>false))
			->add('devicePurchaseDate', null ,array('label'=>"Fecha de Compra del dispositivo",'required'=>true,
				'attr' => array('class' => 'devicePurchaseDate'),
            	'widget' => 'single_text',
            	'format' => 'dd/MM/y',
				))
			->add('deviceManufactureDate', null ,array('data' => new \DateTime(), 'label'=>"Fecha de Fabricación",'required'=>false,
				'attr' => array('class' => 'deviceManufactureDate',  'readonly' => 'readonly'),
            	'widget' => 'single_text',
            	'format' => 'dd/MM/y',
            	//'format' => 'y-MM-dd',
				))				
			
			
			//DEVICE END	
			->add('repairEntryType', null, array('label'=>"Tipo de ingreso", 'required'=>true))
			->add('invoiceNumber', null, array('label'=>"Número de Factura", 'required'=>true))
			->add('humidity', null, array('label'=>"Ingreso por Humedad", 'required'=>false))
			
			;
			
			

			//operator filter
			if(intval($options["operator"]) != 0){
				
				$builder->add('operator', null, array('label'=>"Operador", 'required' => true, 
				'class' => 'Solucel\AdminBundle\Entity\Operator',
				         'query_builder' => function (\Doctrine\ORM\EntityRepository $er)  use ($options){
				                return $er->createQueryBuilder('Operator')
				                    ->where('Operator.id = :param_operator')
				                    ->setParameter('param_operator', $options["operator"]);
				         	
						}
				 )); 
				
			}
			else{
				$builder->add('operator', null, array('label'=>"Operador", 'required' => true, 
				'class' => 'Solucel\AdminBundle\Entity\Operator',
				         'query_builder' => function (\Doctrine\ORM\EntityRepository $er)  use ($options){
				                return $er->createQueryBuilder('Operator')
								->orderBy('Operator.name', 'ASC');
				         	
						}
				 )); 
								
				//$builder->add('operator',  null, array('label'=>"Operador", 'required'=>false));
			}
			
			
			//service center filter
			if(intval($options["center"]) != 0){
				$builder->add('serviceCenter', null, array('label'=>"Centro de Servicio", 'required' => true, 
						'class' => 'Solucel\AdminBundle\Entity\ServiceCenter',
						         'query_builder' => function (\Doctrine\ORM\EntityRepository $er)  use ($options){
						                return $er->createQueryBuilder('ServiceCenter')
						                    ->where('ServiceCenter.id = :param_center')
						                    ->setParameter('param_center', $options["center"]);
						         	
								}
						 )); 
				
			}
			else{
				
				$builder->add('serviceCenter', null, array('label'=>"Centro de Servicio", 'required' => true, 
						'class' => 'Solucel\AdminBundle\Entity\ServiceCenter',
						         'query_builder' => function (\Doctrine\ORM\EntityRepository $er)  use ($options){
						                return $er->createQueryBuilder('ServiceCenter')
										->orderBy('ServiceCenter.name', 'ASC');
						         	
								}
						 )); 				
				//$builder->add('serviceCenter',  null, array('label'=>"Centro de Servicio", 'required'=>false));	
			}			


			//brand filter
			if(intval($options["brand"]) != 0){
				
				$builder->add('deviceBrand', null, array('label'=>"Marca", 'required' => true, 
				'class' => 'Solucel\AdminBundle\Entity\DeviceBrand',
				         'query_builder' => function (\Doctrine\ORM\EntityRepository $er)  use ($options){
				                return $er->createQueryBuilder('DeviceBrand')
				                    ->where('DeviceBrand.id = :param')
				                    ->setParameter('param', $options["brand"])
									->orderBy('DeviceBrand.name', 'ASC');
				         	
						}
				 )); 
				
			}
			else{
				
				$builder->add('deviceBrand', null, array('label'=>"Marca", 'required' => true, 
				'class' => 'Solucel\AdminBundle\Entity\DeviceBrand',
				         'query_builder' => function (\Doctrine\ORM\EntityRepository $er)  use ($options){
				                return $er->createQueryBuilder('DeviceBrand')
									->orderBy('DeviceBrand.name', 'ASC')
									;
				         	
						}
				 )); 				
				//$builder->add('deviceBrand',  null, array('label'=>"Marca", 'required'=>false));
			}
			
			$builder->add('sketchpadData', null, array('label'=>" ", 'required'=>false));
			
			
			/*
			/////device change brand
			//brand filter
			 * ->add('deviceChangeImei', null, array('label'=>"IMEI", 'required'=>false))
			if(intval($options["brand"]) != 0){
				
				$builder->add('deviceChangeBrand', null, array('label'=>"Marca", 'required' => false, 
				'class' => 'Solucel\AdminBundle\Entity\DeviceBrand',
				         'query_builder' => function (\Doctrine\ORM\EntityRepository $er)  use ($options){
				                return $er->createQueryBuilder('DeviceBrand')
				                    ->where('DeviceBrand.id = :param')
				                    ->setParameter('param', $options["brand"]);
				         	
						}
				 )); 
				
			}
			else{
				$builder->add('deviceBrand',  null, array('label'=>"Marca", 'required'=>false));
			}
			
			$builder->add('deviceChangeModel', null, array('label'=>"Modelo", 'required'=>false));
					
			*/
		
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Solucel\AdminBundle\Entity\RepairOrder',
            'center' => null,
            'brand' => null,
            'operator' => null,
            'allow_extra_fields' => true
        ));
    }
}

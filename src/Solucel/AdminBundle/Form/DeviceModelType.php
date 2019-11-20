<?php

namespace Solucel\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeviceModelType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array('label'=>"Nombre", 'required'=>true))
            ->add('isObsolete', null, array('label'=>"Obsoleto", 'required'=>false))
			->add('productCodeIn', null, array('label'=>"Product Code In", 'required'=>true))
			->add('productCodeOut', null, array('label'=>"Product Code Out", 'required'=>true))
            //->add('style', null, array('label'=>"Estilo", 'required'=>true));
            //
            ;
            
			if(intval($options["brand"]) != 0){
				
				$builder->add('deviceBrand', null, array('label'=>"Marca", 'required' => true, 
				'class' => 'Solucel\AdminBundle\Entity\DeviceBrand',
				         'query_builder' => function (\Doctrine\ORM\EntityRepository $er)  use ($options){
				                return $er->createQueryBuilder('DeviceBrand')
				                    ->where('DeviceBrand.id = :param')
				                    ->setParameter('param', $options["brand"]);
				         	
						}
				 )); 
				
			}
			else{
				$builder->add('deviceBrand', null, array('label'=>"Marca de Dispositivo", 'required'=>true));
			}            
        
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Solucel\AdminBundle\Entity\DeviceModel',
            'brand' => null,
        ));
    }
}

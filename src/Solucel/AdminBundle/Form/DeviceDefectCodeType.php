<?php

namespace Solucel\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeviceDefectCodeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('defectCode', null, array('label'=>"Código", 'required'=>true))
            ->add('deviceDefect', null, array('label'=>"Defecto", 'required'=>true));
            //->add('deviceBrand', null, array('label'=>"Marca", 'required'=>true))
			//brand filter
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
				$builder->add('deviceBrand',  null, array('label'=>"Marca", 'required'=>true));
			}            
            
        
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Solucel\AdminBundle\Entity\DeviceDefectCode',
            'brand' => null,
        ));
    }
}

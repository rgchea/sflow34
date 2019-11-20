<?php

namespace Solucel\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        	->add('username',  null, array('label'=>"Usuario", 'required'=>true))
            ->add('name',  null, array('label'=>"Nombre", 'required'=>true))
            ->add('lastName',  null, array('label'=>"Apellido", 'required'=>true))
            ->add('role',  null, array('label'=>"Rol", 'required'=>true))
			->add('serviceCenter',  null, array('label'=>"Centro de Servicio", 'required'=>false))
			->add('deviceBrand',  null, array('label'=>"Marca", 'required'=>false))
			->add('operator',  null, array('label'=>"Operador", 'required'=>false))
            ->add('email',  null, array('label'=>"Email", 'required'=>true))
            ->add('enabled', null, array('label'=>"Habilitado", 'required' => false))
			->add('password', PasswordType::class, array('label'=>"Contraseña", 'required' => false));
			//->add('serviceCenter',  null, array('label'=>"Centro de Servicio", 'required'=>false))
			
			/*
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
				$builder->add('serviceCenter',  null, array('label'=>"Centro de Servicio", 'required'=>false));	
			}
			


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
				$builder->add('operator',  null, array('label'=>"Operador", 'required'=>false));
			}
			

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
				$builder->add('deviceBrand',  null, array('label'=>"Marca", 'required'=>false));
			}
			 * */

			
            //->add('createdAt', 'datetime')
        
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Solucel\AdminBundle\Entity\User',
            'center' => null,
            'brand' => null,
            'operator' => null,
            'role' => null
        ));
    }
}

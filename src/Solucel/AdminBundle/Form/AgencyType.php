<?php

namespace Solucel\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AgencyType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        	//->add('operator',  null, array('label'=>"Operador", 'required'=>true))
            ->add('name',  null, array('label'=>"Nombre", 'required'=>true))
            ->add('description',  TextareaType::class, array('label'=>"Descripción", 'required'=>false))
			->add('enabled', null, array('label'=>"Habilitado", 'required' => false));
			
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
				$builder->add('operator',  null, array('label'=>"Operador", 'required'=>true));
			}
						
			//->add('createdAt')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Solucel\AdminBundle\Entity\Agency',
            'operator' => null
        ));
    }




}

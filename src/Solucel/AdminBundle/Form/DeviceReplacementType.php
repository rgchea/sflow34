<?php

namespace Solucel\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class DeviceReplacementType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        	->add('replacementCode', null, array('label'=>"Código", 'required'=>true), array('data' => 0))
            ->add('name', null, array('label'=>"Nombre", 'required'=>true))
            ->add('description', TextareaType::class, array('label'=>"Descripción", 'required'=>false))
            ->add('deviceReplacementType', null, array('label'=>"Tipo de Repuesto", 'required'=>true))
			->add('deviceBrand', null, array('label'=>"Marca", 'required'=>true))
			->add('deviceModel', null, array('label'=>"Modelo", 'required'=>true))
			
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Solucel\AdminBundle\Entity\DeviceReplacement'
        ));
    }
}

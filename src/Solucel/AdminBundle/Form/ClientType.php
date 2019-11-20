<?php

namespace Solucel\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ClientType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        	->add('clientCode',  null, array('label'=>"Código", 'required'=>true),  array('data' => 0))
            ->add('name',  null, array('label'=>"Nombre", 'required'=>true))
            ->add('lastName',  null, array('label'=>"Apellido", 'required'=>true))
			->add('email',  null, array('label'=>"Correo", 'required'=>false))
            ->add('dpi',  null, array('label'=>"DPI", 'required'=>true))
			->add('nit',  null, array('label'=>"NIT", 'required'=>true))
            ->add('phone',  null, array('label'=>"Número de Teléfono (entregado)", 'required'=>true))
            ->add('contactPhone',  null, array('label'=>"Teléfono Contacto", 'required'=>false))
            ->add('contactPhoneOther', null, array('label'=>"Otro Teléfono", 'required'=>false))
            ->add('enabled', null, array('label'=>"Habilitado", 'required' => false))
            //->add('createdAt', 'datetime')
            ->add('clientType', ChoiceType::class,
            	array('label'=>"Tipo de Cliente", 'choices' => array("Operador" => "Operador", "Distribuidor" => "Distribuidor", "Proveedor" => "Proveedor", "Particular" => "Particular"),
					'required'=>true) 
			)
			->add('state',  null, array('label'=>"Municpio", 'required'=>true))
        ;
		
			
		
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Solucel\AdminBundle\Entity\Client'
        ));
    }
}

<?php

namespace Solucel\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class DeviceFixTypeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',  null, array('label'=>"Nombre", 'required'=>true))
            ->add('description',  TextareaType::class, array('label'=>"Descripción", 'required'=>false))
            ->add('deviceFixLevel', null, array('label'=>"Nivel de Reparación", 'required'=>true))
            ->add('transactionCode', null, array('label'=>"Transaction Code", 'required'=>true))
            ->add('deviceBrand',  null, array('label'=>"Marca", 'required'=>true))
            ->add('price',  null, array('label'=>"Costo", 'required'=>true))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Solucel\AdminBundle\Entity\DeviceFixType'
        ));
    }
}

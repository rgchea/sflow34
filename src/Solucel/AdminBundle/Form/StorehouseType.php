<?php

namespace Solucel\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class StorehouseType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array('label'=>"Nombre", 'required'=>true))
			->add('storehouseCode', null, array('label'=>"Código", 'required'=>true))
            ->add('description', TextareaType::class, array('label'=>"Descripción", 'required'=>false))
            ->add('enabled', null, array('label'=>"Habilitado", 'required' => false))
            

            //->add('createdAt', 'datetime')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Solucel\AdminBundle\Entity\Storehouse'
        ));
    }
}

<?php

namespace Solucel\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class OperatorType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array('label'=>"Nombre", 'required'=>true))
            ->add('description', TextareaType::class, array('label'=>"Descripción", 'required'=>false))
            ->add('enabled', null, array('label'=>"Habilitado", 'required' => false))
            //->add('createdAt', 'datetime')
            ->add('daysToFixDevice', null, array('label'=>"Días para reparar 1 dispositivo", 'required' => false))
            ->add('logoPath', null, array('label'=>"Logo (Imagen)", 'required'=>false))
            ->add('file', FileType::class, array('label'=>"Logo (Imagen)", 'required'=>false))
			
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Solucel\AdminBundle\Entity\Operator'
        ));
    }
}

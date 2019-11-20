<?php

namespace Solucel\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TransactionCodeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('transactionCode',  null, array('label'=>"Transaction Code", 'required'=>true))
        ->add('description',  TextareaType::class, array('label'=>"Description", 'required'=>false))
        ->add('serviceRepair', ChoiceType::class,
        	array('label'=>"Service / Repair", 'choices' => array("S" => "S", "R" => "R"),
				'required'=>true) 
		)
		
		;        
		
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Solucel\AdminBundle\Entity\TransactionCode'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'solucel_adminbundle_transactioncode';
    }


}

<?php

namespace Solucel\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RepairOrderDeliveryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        
        		->add('deliveryDateSent', null ,array('data' => new \DateTime(), 'label'=>"Fecha de envío",'required'=>true,
				'attr' => array('class' => 'deliveryDateSent',  'readonly' => 'readonly'),
            	'widget' => 'single_text',
            	//'format' => 'dd/MM/y',
            	'format' => 'y-MM-dd H:m:s',
				))
        		//->add('deliveryDateReceived')
        		->add('repairOrder', null, array('label'=>"Orden/Boleta", 'required'=>true))
        		->add('deliveryFromAgency', null, array('label'=>"Desde Agencia", 'required'=>false))
        		->add('deliveryToServiceCenter', null, array('label'=>"Hacia Centro", 'required'=>false))
        		->add('deliveryFromServiceCenter', null, array('label'=>"Desde Centro", 'required'=>false))
        		->add('deliveryToAgency', null, array('label'=>"Hacia Agencia", 'required'=>false))
				;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Solucel\AdminBundle\Entity\RepairOrderDelivery'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'solucel_adminbundle_repairorderdelivery';
    }


}

<?php

namespace Solucel\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RepairOrderFixType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('version')
            ->add('fixDetail', null, array('label'=>"Detalle de Reparación", 'required'=>true))
            ->add('deviceReceivedStatus', null, array('label'=>"Estado físico", 'required'=>false))
            ->add('warrantyComment', null, array('label'=>"Comentario sobre garantía", 'required'=>false))
            ->add('fixingPrice', null, array('label'=>"Precio de la reparación", 'required'=>true))
            ->add('imeiNewDeviceChange', null, array('label'=>"IMEI de nuevo dispositivo", 'required'=>false))
            ->add('serialNumberChange', null, array('label'=>"No Serie de nuevo dispositivo", 'required'=>false))
            ->add('deviceBrandChange', null, array('label'=>"Marca de nuevo dispositivo", 'required'=>false))
			->add('deviceModelChange', null, array('label'=>"Modelo de nuevo dispositivo", 'required'=>false))
            //->add('assignedBy')
            //->add('assignedTo')
            //->add('repairOrder')
            ->add('hasWarranty', ChoiceType::class, array('choices' => array(1 => 'Con Garantía', 0 => 'Sin Garantía'), 'label'=>"Garantía", 'required'=>true ))
			
			->add('isStorehouse', null, array('label'=>"Bodega", 'required'=>false))
			->add('requisitionNumber', null, array('label'=>"No Requisición", 'required'=>false))
			->add('softwareOut', null, array('label'=>"Software Out", 'required'=>false))
			->add('actionReasonCode', null, array('label'=>"Action Reason Code", 'required'=>false))
			->add('problemFoundCode', null, array('label'=>"Problem Found Code", 'required'=>false))
			->add('transactionCode', null, array('label'=>"Transaction Code", 'required'=>false))

        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Solucel\AdminBundle\Entity\RepairOrderFix'
        ));
    }
}

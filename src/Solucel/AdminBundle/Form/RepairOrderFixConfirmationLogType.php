<?php

namespace Solucel\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RepairOrderFixConfirmationLogType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('logComment', null, array('label'=>"Comentario sobre llamada", 'required'=>true))
				->add('clientConfirmation', ChoiceType::class, array('choices' => array(1 => 'Aceptó reparación', 0 => 'Denegó repración'), 'label'=>"Respuesta de Cliente", 'required'=>true ));
        		//->add('createdAt')
        		//->add('enabled')
        		//->add('createdBy');
//        		->add('repairOrderFix');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Solucel\AdminBundle\Entity\RepairOrderFixConfirmationLog'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'solucel_adminbundle_repairorderfixconfirmationlog';
    }


}

<?php

namespace Solucel\MyFOSUserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\BaseType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RegistrationFormType extends AbstractType
{

    private $class;

    /**
     * @param string $class The User class name
     */
    public function __construct($class)
    {
    	
        $this->class = $class;
    }
    
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        
		/*
		 * $builder->add('firstName','text',array("label"=>"First Name"));
        $builder->add('lastName','text',array("label"=>"Last Name"));        
         parent::buildForm($builder, $options);
        $builder->add('phone','text',array('required'=>false));
     //   $builder->add('locale',ChoiceType::class,array('required'=>false));
                $builder->add('country', 'entity', array(
                 'label' => 'Country',
                 'property' => 'name',
                 'empty_value'=>'Select Country',
                 'class' => 'RootGeodirectoryBundle:Country',
                 'query_builder' => function(EntityRepository $er) {
                     return $er->createQueryBuilder('c')->where('c.isActive = :isActive')->setParameter('isActive', '1')->orderBy('c.name', 'ASC');
                 },
             ));
         $builder->add('termsAccepted','checkbox',array("label"=>" I agree to the Terms and Conditions and Privacy Policy"));   
		 * */
		 
        $builder
            ->add('email', 'email', array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
            ->add('username', null, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle'))
			//->add('role',  ChoiceType::class, array('choices'   => array('ROLE_CLIENT' => 'CLIENTE', 'ROLE_OPERATOR' => 'TRANSPORTISTA'),'required'  => true,))
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => array('label' => 'form.password'),
                'second_options' => array('label' => 'form.password_confirmation'),
                'invalid_message' => 'fos_user.password.mismatch',
            ))
        ;
		
		 		 
    
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Wbc\AdministratorBundle\Entity\User',
            'intention'  => 'registration',
        ));
    }    


    public function getName()
    {
        return 'opera_user_registration';
    }
}
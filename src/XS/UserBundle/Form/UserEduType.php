<?php

namespace XS\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use XS\CoreBundle\Form\GMapsType;
use XS\CoreBundle\Form\LocalisationType;
use XS\EducationBundle\Form\UserType;

class UserEduType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('nickname', TextType::class, array(
        'required' => true,
        'attr' => array(
          'maxlength' => 10
        )
      ))
//            Ajout des info telles que sur la CNI
      ->add('name', TextType::class, array(
        'required' => false
      ))
      ->add('surname', TextType::class, array(
        'required' => false
      ))
      ->add('idCard', TextType::class, array(
        'required' => false
      ))
      ->add('idCardDeliveryDate', DateType::class, array(
        'required' => false
      ))
      ->add('idCardDeliveryLocation', LocalisationType::class, array(
        'required' => false
      ))
//            Fin d'ajout des infos telles que sur la CNI
      ->add('edu', UserType::class, array(
        'required' => false
      ))
       ->add('email', EmailType::class, array(
        'required' => false
      ))
      
      ->add('username', TextType::class, array(
        'required' => false,
        'attr' => array(
          'id' => 'username',
          'pattern' =>  '^((242|243)[0-9]{6})|((65|66|67|68|69)[0-9]{7})$',
        )
      ))
      ->add('gender', ChoiceType::class, array(
        'required' => true,
        'choices' => array_flip(array(
          'Male' => 'Homme',
          'Female' => 'Femme'
        ))
      ))
      ->add('localisation', LocalisationType::class, array(
        'required' => false
      ))
      ->add('gmaps', GMapsType::class, array(
        'required' => false
      ))
    ;
    
  }
  
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'XS\UserBundle\Document\User'
    ));
  }
  
  public function getName()
  {
    return 'xsuser_bundle_user_edu_type';
  }
}

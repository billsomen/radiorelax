<?php

namespace XS\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('namespace', TextType::class, array(
        'required' => false,
        'attr' => array(
          'maxLength' => 35
        )
      ))
      ->remove('terms')
//      ->remove('username')
      ->remove('registrationNumber')
      ->remove('password')
      ->remove('profile')
    ;
  }
  
  public function getParent() {
    return UserType::class;
  }
  
  public function getName()
  {
    return 'xsuser_bundle_user_edit_type';
  }
}

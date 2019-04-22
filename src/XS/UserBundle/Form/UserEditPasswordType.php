<?php

namespace XS\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditPasswordType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->remove('password')
      ->add('oldPassword', PasswordType::class)
      ->add('password', RepeatedType::class, array(
       'type' => PasswordType::class,
       'invalid_message' => 'Les deux mots de passe doivent correspondre',
       'options' => array(
         'required' => true
       ),
     ))
      ->remove('terms')
      
      ->remove('name')
      ->remove('firstname')
      ->remove('nickname')
      ->remove('email')
      ->remove('telephones')
      ->remove('profile')
      ->remove('localisation')
      ->remove('birthdate')
      ->remove('gender')
      ->remove('fb_id')
      ->remove('username')
      ->remove('profile')
    ;
  }
  
  public function getParent() {
    return UserType::class;
  }
  
  public function getName()
  {
    return 'xsuser_bundle_user_edit_password_type';
  }
}

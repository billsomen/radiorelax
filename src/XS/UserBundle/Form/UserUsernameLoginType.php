<?php

namespace XS\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserUsernameLoginType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('username', TextType::class, array(
        'required' => true,
        'attr' => array(
          'autofocus' => 'autofocus',
          'pattern' =>  '^((242|243)[0-9]{6})|((65|66|67|68|69)[0-9]{7})$',
        )
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
    return 'xsuser_bundle_user_username_login_type';
  }
}

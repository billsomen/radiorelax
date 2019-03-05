<?php

namespace XS\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use XS\CoreBundle\Form\DocumentType;
use XS\CoreBundle\Form\LocalisationType;
use XS\UserBundle\Document\User;

class UserType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('nickname', TextType::class, array(
        'required' => true,
        'attr' => array(
          'class' => "form-control",
          'maxlength' => 10,
          'minlength' => 5,
          'placeholder' => "xs_user.user.nickname"
        )
      ))
      ->add('username', EmailType::class, array(
        'required' => true,
        'attr' => array(
          'class' => "form-control",
          'placeholder' => "xs_user.user.username"
        )
      ))
    ;
  }
  
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => User::class
    ));
  }
  
  public function getName()
  {
    return 'xsuser_bundle_user_type';
  }
}

<?php

namespace XS\MarketPlaceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use XS\MarketPlaceBundle\Document\StoreContact;
use XS\UserBundle\Form\TelephoneType;

class StoreContactType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('telephones', CollectionType::class, array(
        'entry_type' => TelephoneType::class,
        'allow_add' => true,
        'required' => false,
        'prototype' => true
      ))
      ->add('websites', CollectionType::class, array(
        'entry_type' => TextType::class,
        'allow_add' => true,
        'required' => false,
        'prototype' => true,
        'entry_options' => array(
          'attr' => array(
            'class' => 'form-control',
            'placeholder' => 'Enter Websites'
          )
        )
      ))
      ->add('email', EmailType::class, array(
        'required' => false,
        'attr' => array(
          'class' => 'form-control select',
        )
      ))
    ;
  }
  
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => StoreContact::class
    ));
  }
  
  public function getName()
  {
    return 'main_bundle_store_contact_type';
  }
}

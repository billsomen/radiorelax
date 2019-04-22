<?php

namespace XS\MarketPlaceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use XS\CoreBundle\Form\GMapsType;
use XS\CoreBundle\Form\LocalisationType;
use XS\MarketPlaceBundle\Document\Store;

class StoreType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('name', TextType::class, array(
        'attr' => array(
          'class' => 'form-control'
        )
      ))
      ->add('namespace', TextType::class, array(
        'required' => false
      ))
    /*  ->add('contacts', StoreContactType::class, array(
        'required' => false
      ))*/
      ->add('localisation', LocalisationType::class, array(
        'required' => false
      ))
      ->add('gmaps', GMapsType::class, array(
        'required' => false
      ))
      ->add('description', TextareaType::class, array(
        'attr' => array(
          'class' => 'form-control'
        )
      ))
      ->add('type', ChoiceType::class, array(
        'required' => false,
        'choices' => array_flip(array(
          'basic' => 'Boutique basique',
          'pro' => 'Boutique professionnelle',
          'standard' => 'Boutique standard'
        ))
      ))
      ->add('terms', CheckboxType::class, array(
        'required' => true
      ))
    ;
  }
  
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => Store::class
    ));
  }
  
  public function getName()
  {
    return 'main_bundle_store_type';
  }
}

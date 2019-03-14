<?php

namespace XS\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use XS\UserBundle\Document\Locale;

class LocaleType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('language', HiddenType::class,  array(
        'required' => false
      ))
      ->add('timeZoneId', HiddenType::class,  array(
        'required' => false
      ))
      ->add('timeZoneName', HiddenType::class,  array(
        'required' => false
      ))
      ->add('dstOffset', HiddenType::class,  array(
        'required' => false
      ))
      ->add('rawOffset', HiddenType::class,  array(
        'required' => false
      ))
      ->add('currency', HiddenType::class,  array(
        'required' => false
      ))
    ;
  }
  
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => Locale::class
    ));
  }
  
  public function getBlockPrefix()
  {
    return 'xsuser_bundle_locale_type';
  }
}

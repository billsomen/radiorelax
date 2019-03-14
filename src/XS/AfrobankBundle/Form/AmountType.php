<?php

namespace XS\AfrobankBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AmountType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('value', IntegerType::class, array(
        'attr' => array(
          'class' => 'form-control form-control-alternative',
          'min' => 0,
          'step' => 0.001,
        ),
      ))
    ;
    
  }
  
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'XS\AfrobankBundle\Document\Amount'
    ));
    
  }
  
  public function getName()
  {
    return 'xsafrobank_bundle_amount_type';
  }
}

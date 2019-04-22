<?php

namespace XS\AfrobankBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionPaymentUpdateType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('amount', AmountType::class)
      ->add('fee', IntegerType::class, array(
        'required' => true,
        'attr' => array(
          'min' => 0,
          'class' => 'form-control'
        )
      ))
      ->add('description', TextType::class, array(
        'required' => true,
        'attr' => array(
          'class' => 'form-control',
          'rows' => 2,
        )
      ))
    ;
  }
  
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'XS\AfrobankBundle\Document\Transaction',
      'allow_extra_fields' => true
    ));
  }
  
  public function getBlockPrefix()
  {
    return 'xsafrobank_bundle_transaction_payment_update_type';
  }
}

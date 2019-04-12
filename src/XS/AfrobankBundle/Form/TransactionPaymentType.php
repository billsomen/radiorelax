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

class TransactionPaymentType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('amount', AmountType::class)
      ->add('fee', IntegerType::class, array(
        'required' => false,
        'attr' => array(
          'min' => 0,
          'class' => 'form-control'
        )
      ))
      ->add('localCode', TextType::class, array(
        'required' => false,
        'attr' => array(
          'class' => 'form-control'
        )
      ))
      ->add('description', TextType::class, array(
        'required' => false,
        'attr' => array(
          'class' => 'form-control',
          'rows' => 2,
        )
      ))
      /*->add('type', 'choice', array(
        'choices' => array(
          'deposit' => 'Dépôt',
        )
      ))*/
      ->add('bank', HiddenType::class, array(
        'required' => false
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
    return 'xsafrobank_bundle_transaction_payment_type';
  }
}

<?php

namespace XS\AfrobankBundle\Form;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionSendType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      /* ->add('receiver', 'document', array(
           'class' => 'XS\UserBundle\Document\User'
       ))*/
      ->add('amount', new AmountType())
      ->add('receiver', 'document', array(
          'class' => 'XS\UserBundle\Document\User')
      )
    ;
  }
  
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'XS\AfrobankBundle\Document\Transaction',
      'allow_extra_fields' => true
    ));
  }
  
  public function getName()
  {
    return 'xsafrobank_bundle_transaction_send';
  }
}

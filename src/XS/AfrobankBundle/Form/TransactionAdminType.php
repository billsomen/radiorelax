<?php

namespace XS\AfrobankBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount', new AmountType())
            ->add('type', 'choice', array(
                'choices' => array(
                    'deposit' => 'Dépôt',
                )
            ))
            ->add('bank', 'choice', array(
                'choices' => array(
                    "orange_money" => 'Orange Money',
                    "mtn_mobile_money" => 'MTN Mobile Money',
                    "deposit_store" => 'Dépôt à la Boutique',
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

    public function getName()
    {
        return 'xsafrobank_bundle_transaction_admin_type';
    }
}

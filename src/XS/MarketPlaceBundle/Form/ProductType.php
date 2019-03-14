<?php

namespace XS\MarketPlaceBundle\Form;

use Symfony\Component\Finder\Comparator\NumberComparator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use XS\AfrobankBundle\Form\AmountType;
use XS\CoreBundle\Form\LocalisationType;
use XS\MarketPlaceBundle\Document\Product;

class ProductType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('name', TextType::class, array(
        'attr' => array(
          'class' => 'form-control form-control-alternative',
          'placeholder' => 'Nom du produit'
        ),
      ))
      ->add('profileUrl', FileType::class, array(
        'required' => false,
        'data_class' => null
      ))
      ->add('photosUrls', CollectionType::class, array(
        'entry_type' => FileType::class,
        'allow_add' => true,
        'required' => false,
        'prototype' => true,
        'entry_options' => array(
          'data_class' => null,
          'attr' => array(
            'class' => 'form-control',
            'placeholder' => 'Url of Photo of item'
          )
        )
      ))
      ->add('quantity', IntegerType::class, array(
        'attr' => array(
          'class' => 'form-control',
          'min' => 1
        ),
        'required' => false,
      ))
      ->add('price_min', AmountType::class, array(
        'required' => false,
      ))
      ->add('priceShown', AmountType::class)
      ->add('payLoan', CheckboxType::class, array(
        'required' => false,
      ))
      ->add('payFrequency', ChoiceType::class, array(
          'required' => false,
          'attr' => array(
            'class' => 'form-control'
          ),
          'choices' => array(
            Product::PAY_ONCE => Product::PAY_ONCE,
            Product::PAY_YEAR => Product::PAY_YEAR,
            Product::PAY_MONTH => Product::PAY_MONTH,
            Product::PAY_WEEK => Product::PAY_WEEK,
            Product::PAY_DAY => Product::PAY_DAY,
            Product::PAY_HOUR => Product::PAY_HOUR,
          )
        )
      )
      ->add('state', ChoiceType::class, array(
        'required' => false,
        'choices' => array_flip(
          array(
            'new' => 'Nouveau',
            'renew' => 'Rénauvé',
            'second_hand' => 'Deuxième main',
          )
        )))
      ->add('stateDuration', IntegerType::class, array(
//            'placeholder' => 'How long (in days) have you made with this product ?'
        'required' => false,
        'attr' => array(
          'class' => 'form-control',
          'placeholder' => 'en jours'
        ),
      ))
      ->add('stateDescription', TextareaType::class, array(
        'attr' => array(
          'class' => 'form-control'
        ),
        'required' => false
      ))
      ->add('localisation', LocalisationType::class, array(
        'required' => false,
      ))
      ->add('house', StandardHouseType::class, array(
        'required' => true,
      ))
      ->add('description', TextareaType::class, array(
        'attr' => array(
          'class' => 'form-control form-control-alternative',
          'placeholder' => 'Informations sur le produit...'
        ),
        'required' => true
      ))
    ;
    
  }
  
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => Product::class
    ));
  }
  
  public function getName()
  {
    return 'main_bundle_product_type';
  }
}

<?php

namespace XS\MarketPlaceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use XS\MarketPlaceBundle\Document\Room;

class RoomType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('office', IntegerType::class, array(
          'attr' => array(
            'class' => 'form-control',
            'min' => 0,
            'max' => 99,
            'placeholder' => "Quantité"
          )
        )
      )
      ->add('livingRoom', IntegerType::class, array(
          'attr' => array(
            'class' => 'form-control',
            'min' => 0,
            'max' => 99,
            'placeholder' => "Quantité"
          )
        )
      )
      ->add('kitchen', IntegerType::class, array(
          'attr' => array(
            'class' => 'form-control',
            'min' => 0,
            'max' => 99,
            'step' => 1,
            'placeholder' => "Quantité"
          )
        )
      )
      ->add('diningRoom', IntegerType::class, array(
          'attr' => array(
            'class' => 'form-control',
            'min' => 0,
            'max' => 99,
            'step' => 1,
            'placeholder' => "Quantité"
          )
        )
      )
      ->add('toilet', IntegerType::class, array(
          'attr' => array(
            'class' => 'form-control',
            'min' => 0,
            'max' => 99,
            'step' => 1,
            'placeholder' => "Quantité"
          )
        )
      )
      ->add('bedRoom', IntegerType::class, array(
          'attr' => array(
            'class' => 'form-control',
            'min' => 0,
            'max' => 99,
            'step' => 1,
            'placeholder' => "Quantité"
          )
        )
      )
    ;
    
  }
  
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(
      array(
        'data_class' => Room::class
      )
    );
  }
  
  public function getName()
  {
    return 'market_place_bundle_room_type';
  }
}

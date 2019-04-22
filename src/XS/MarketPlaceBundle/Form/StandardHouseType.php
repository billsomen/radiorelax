<?php

namespace XS\MarketPlaceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use XS\MarketPlaceBundle\Document\StandardHouse;

class StandardHouseType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('type', ChoiceType::class, array(
        'choices' => array_flip(array(
          'bed_room' => 'Chambre',
          'bed_room_modern' => 'Chambre Moderne',
          'studio' => 'Studio',
          'apartment' => 'Appartement',
          'house' => 'Maison',
          'field' => 'Terrain',
          'commercial' => 'Local commercial'
        )),
        'attr' => array(
          'class' => 'form-control select',
        )
      ))
      ->add('surface_living', IntegerType::class, array(
        'attr' => array(
          'class' => 'form-control',
          'placeholder' => 'Surface du site'
        )
      ))
      ->add('surface_total', IntegerType::class, array(
        'attr' => array(
          'class' => 'form-control',
          'placeholder' => 'Superficie totale'
        )
      ))
      ->add('parking', IntegerType::class, array(
        'attr' => array(
          'class' => 'form-control',
          'min' => 0,
          'max' => 99,
          'step' => 1,
          'placeholder' => 'Places de parking : 0, 1, 2, etc...'
        )
      ))
      ->add('distanceToRoad', IntegerType::class, array(
        'attr' => array(
          'class' => 'form-control',
          'min' => 0,
          'step' => 0.001,
          'placeholder' => 'Distance de la route (en m)'
        )
      ))
      ->add('distanceSchool', IntegerType::class, array(
        'attr' => array(
          'class' => 'form-control',
          'min' => 0,
          'step' => 0.001,
          'placeholder' => "Distance de l'école"
        ),
        'required' => false
      ))
      ->add('furnished', CheckboxType::class, array(
        'required' => false,
      ))
      ->add('length', IntegerType::class, array(
        'attr' => array(
          'class' => 'form-control',
          'min' => 0,
          'step' => 0.001,
          'placeholder' => 'Longueur totale (en m)'
        )))
      ->add('width', IntegerType::class, array(
        'attr' => array(
          'class' => 'form-control',
          'min' => 0,
          'step' => 0.001,
          'placeholder' => 'Largeur totale (en m)'
        )))
      ->add('height', IntegerType::class, array(
        'required' => false,
        'attr' => array(
          'class' => 'form-control',
          'min' => 0,
          'step' => 0.001,
          'placeholder' => 'Hauteur totale (en m) - optionnel'
        )))
      ->add('room', RoomType::class)
    ;
    
  }
  
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(
      array(
        'data_class' => StandardHouse::class
      )
    );
  }
  
  public function getName()
  {
    return 'main_bundle_standard_house_type';
  }
}

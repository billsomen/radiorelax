<?php

namespace MainBundle\Form;

use MainBundle\Document\Artist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use XS\CoreBundle\Form\GMapsType;
use XS\CoreBundle\Form\LocalisationType;

class ArtistType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('name', TextType::class, array(
        'required' => true,
        'attr' => array(
          'class' => "form-control form-control-alternative ",
          'maxlength' => 10,
          'minlength' => 5,
          'placeholder' => "Nom d'utilisateur"
        )
      ))
     /* ->add('localisation', LocalisationType::class, array(
        'required' => false
      ))*/
      ->add('gmaps', GMapsType::class, array(
        'required' => false
      ))
      ->add('link', TextType::class, array(
        'required' => true,
        'attr' => array(
          'class' => "form-control form-control-alternative",
          'placeholder' => "Nom d'utilisateur"
        )
      ))
      ->add('email', TextType::class, array(
        'required' => true,
        'attr' => array(
          'class' => "form-control form-control-alternative",
          'placeholder' => "Nom d'utilisateur"
        )
      ))
      ->add('description', TextareaType::class, array(
        'required' => true,
        'attr' => array(
          'class' => "form-control form-control-alternative",
          'placeholder' => "Nom d'utilisateur"
        )
      ))
    ;
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => Artist::class
    ));
  }

  public function getBlockPrefix()
  {
    return 'admin_bundle_artist_type';
  }
}

<?php

namespace MainBundle\Form;

use MainBundle\Document\Music;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MusicType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('name', TextType::class, array(
        'required' => true,
        'attr' => array(
          'class' => "form-control form-control-alternative",
          'placeholder' => "All right"
        )
      ))
      ->add('desc', TextareaType::class, array(
        'required' => false,
        'attr' => array(
          'class' => "form-control form-control-alternative",
          'placeholder' => "..."
        )
      ))
      ->add('dateRelease', DateType::class, array(
        'html5' => true,
        'required' => false,
        'widget' => 'single_text',
        'attr' => array(
          'class' => 'form-control form-control-alternative'
        )
      ))
    ;
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => Music::class
    ));
  }

  public function getBlockPrefix()
  {
    return 'radio_relax_admin_bundle_music_type';
  }
}

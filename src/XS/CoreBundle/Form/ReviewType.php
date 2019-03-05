<?php

namespace XS\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use XS\CoreBundle\Document\Review;

class ReviewType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('quality', ChoiceType::class,
        array(
          'required' => true,
          'choices'   => array(
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
            5 => 5,
          ),
          'multiple'  => false,
          'expanded'  => true,
        )
      )
      ->add('value', ChoiceType::class, array(
          'required' => true,
          'choices'   => array(
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
            5 => 5,
          ),
          'multiple'  => false,
          'expanded'  => true,
        )
      )
      ->add('price', ChoiceType::class, array(
          'required' => true,
          'choices' => array(
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
            5 => 5,
          ),
          'multiple'  => false,
          'expanded'  => true,
        )
      )
      ->add('content', TextareaType::class, array(
        'attr' => array(
          'class' => 'form-control autosizeme',
          'rows' => '6',
          'cols' => '5',
          'maxlength' => 250,
          'placeholder' => 'Je pense que...'
        )
      ))
      ->add('title', TextType::class, array(
        'attr' => array(
          'class' => 'form-control',
          'maxlength' => 50,
          'placeholder' => 'Logement haut standing...'
        )
      ))
      ->add('userName', TextType::class, array(
        'attr' => array(
          'class' => 'form-control',
          'maxlength' => 50,
          'placeholder' => "Nom d'utilisateur"
        )
      ))
    ;
  }
  
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => Review::class
    ));
  }
  
  public function getBlockPrefix()
  {
    return 'xscore_bundle_review_type';
  }
}

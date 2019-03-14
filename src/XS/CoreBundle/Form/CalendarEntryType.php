<?php

namespace XS\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use XS\AfrobankBundle\Form\AmountType;
use XS\CoreBundle\Document\CalendarEntry;

class CalendarEntryType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('name', TextType::class, array(
        'attr' => array(
          'class' => 'form-control',
          'placeholder' => 'Nom'
        )
      ))
      ->add('accessPrice', AmountType::class, array())
      ->add('description', TextareaType::class, array(
        'attr' => array(
          'class' => 'form-control autosizeme',
          'rows' => 4,
          'placeholder' => 'Description'
        )
      ))
      ->add('timeFrom', TextType::class, array(
        'attr' => array(
          'class' => 'form-control timepicker timepicker-24',
        )
      ))
      ->add('timeTo', TextType::class, array(
        'attr' => array(
          'class' => 'form-control timepicker timepicker-24',
        )
      ))
      ->add('dateFrom', DateType::class, array(
        'html5' => true,
        'widget' => 'single_text',
        'attr' => array(
          'class' => 'form-control'
        )
      ))
      ->add('placesTotal', IntegerType::class, array(
        'attr' => array(
          'class' => 'form-control',
          'placeholder' => 'Nombre max de places',
          'min' => 1,
          'max' => 10,
          'step' => 1,
        ),
      ))
      ->add('dateTo', DateType::class, array(
        'html5' => true,
        'widget' => 'single_text',
        'attr' => array(
          'class' => 'form-control'
        )
      ))
      ->add('ranges', CollectionType::class, array(
        'entry_type' => CalendarEntryRangeType::class,
        'allow_add' => true,
        'required' => false,
      ))
      ->add('days', ChoiceType::class, array(
        'choices'   => array(
          'Lundi' => "1",
          'Mardi' => "2",
          'Mercredi' => "3",
          'Jeudi' => "4",
          'Vendredi' => "5",
          'Samedi' => "6",
          'Dimanche' => "0",
        ),
        'required' => true,
        'multiple'  => true,
        'expanded'  => true,
      ))
    ;
  }
  
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => CalendarEntry::class
    ));
  }
  
  public function getBlockPrefix()
  {
    return 'xscore_bundle_calendar_entry_type';
  }
}

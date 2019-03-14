<?php

namespace XS\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use XS\CoreBundle\Document\CalendarEntryRange;

class CalendarEntryRangeType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('dateFrom', DateType::class, array(
        'html5' => true,
        'widget' => 'single_text',
        'attr' => array(
          'class' => 'form-control'
        )
      ))
      ->add('dateTo', DateType::class, array(
        'html5' => true,
        'widget' => 'single_text',
        'attr' => array(
          'class' => 'form-control'
        )
      ))
    ;
  }
  
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => CalendarEntryRange::class
    ));
  }
  
  public function getBlockPrefix()
  {
    return 'xscore_bundle_calendar_entry_range_type';
  }
}

<?php

namespace XS\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use XS\CoreBundle\Document\Audience;

class AudienceType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('types', ChoiceType::class, array(
        'required' => false,
        'choices'   => array_flip(array(
          'principal' => 'Principal',
          'teacher' => 'Enseignant',
//                    todo: On retire le type admin, cet utilisateur n'a pas acces a cette fonctionalite :)
//                    'admin' => 'Edu Admin',
          'student' => 'Elève',
          'parent' => 'Parent',
        )),
        'multiple'  => true,
        'expanded'  => true,
      ))
      ->add('genders', ChoiceType::class, array(
        'required' => false,
        'choices' => array_flip(array(
          'Male' => 'Hommes',
          'Female' => 'Femmes'
        )),
        'multiple'  => true,
        'expanded'  => true,
      ))
    ;
  }
  
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => Audience::class
    ));
  }
  
  public function getBlockPrefix()
  {
    return 'xscore_bundle_audience_type';
  }
}

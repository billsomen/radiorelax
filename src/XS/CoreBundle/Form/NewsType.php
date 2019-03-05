<?php

namespace XS\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use XS\CoreBundle\Document\News;

class NewsType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('message', TextareaType::class, array(
        'attr' => array(
          'rows' => '3',
        )
      ))
      ->add('audience', AudienceType::class, array(
        'required' => false
      ))
      ->add('description', TextareaType::class, array(
        'required' => false
      ))
      ->add('type', ChoiceType::class, array(
        'required' => true,
        'choices' => array(
          'Discipline' => 'discipline',
          'Retards' => 'lateness',
          'Absences' => 'abscence',
          'Notes' => 'ratings',
          'Info' => 'infos',
          'Publicité' => 'advert',
          'Autres - Général' => 'others'
        )
      ))
      /*->add('source', ChoiceType::class, array(
        'required' => true,
        'choices' => array(
          'Système' => 'discipline',
          'Excel' => 'lateness',
          'Absences' => 'abscence',
//          'Notes' => 'ratings',
          'Info' => 'infos',
          'Autres' => 'others'
        )
      ))*/
      ->add('channels', ChoiceType::class, array(
        'required' => true,
        'choices' => array_flip(array(
          'sms' => 'SMS',
          'email' => 'Email',
          'messenger' => 'Messenger',
          'telegram' => 'Telegram',
          'system' => 'Edutool',
          'call' => 'Appel'
        )),
        'multiple'  => true,
        'expanded'  => true,
      ))
      ->add('notifyChannels', ChoiceType::class, array(
        'required' => true,
        'choices' => array_flip(array(
          'sms' => 'Par SMS',
          'email' => 'Par Mail',
          'messenger' => 'Par Messenger',
          'telegram' => 'Par Telegram',
          'call' => 'Par Appel'
        )),
        'multiple'  => true,
        'expanded'  => true,
      ))
    ;
  }
  
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => News::class
    ));
  }
  
  public function getBlockPrefix()
  {
    return 'xscore_bundle_news_type';
  }
}

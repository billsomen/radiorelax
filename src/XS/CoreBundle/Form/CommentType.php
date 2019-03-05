<?php

namespace XS\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('content', TextareaType::class, array(
        'attr' => array(
          'class' => 'form-control autosizeme media',
          'rows' => '1',
          'placeholder' => 'Commentaire'
        )
      ))
      ->add('photos', FileType::class, array(
          'required' => false,
          'multiple' => true,
          'data_class' => null,
          'attr' => array(
            'accept' => 'image/*',
            'class' => 'file'
          )
        )
      )
    ;
  }
  
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'XS\CoreBundle\Document\Comment'
    ));
  }
  
  public function getName()
  {
    return 'xscore_bundle_comment_type';
  }
}

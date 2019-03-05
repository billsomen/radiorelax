<?php

namespace XS\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('title', TextType::class, array(
        'attr' => array(
          'class' => 'form-control',
          'placeholder' => 'Titre du Document',
        ),
        'required' => true,
      ))
      ->add('file', FileType::class, array(
        'required' => true,
        'data_class' => null
      ))
    ;
  }
  
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'XS\CoreBundle\Document\Document'
    ));
  }
  
  public function getName()
  {
    return 'xscore_bundle_document_type';
  }
}

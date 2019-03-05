<?php

namespace RadioRelax\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArtistType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {

  }

  public function configureOptions(OptionsResolver $resolver)
  {

  }

  public function getBlockPrefix()
  {
    return 'radio_relax_core_bundle_artist_type';
  }
}

<?php

namespace XS\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GMapsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $builder
        ->add('formattedAddress', TextType::class, array(
          'required' => true,
          'attr' => array(
            'class' => 'form-control',
            'readonly' => 'readonly',
            'id' => 'formatted_address'
          )
        ))
        ->add('lat', TextType::class, array(
          'required' => true,
//          'id' => 'lat',
          'attr' => array(
            'class' => 'form-control',
            'readonly' => 'readonly',
            'id' => 'lat'
          )
        ))
        ->add('lng', TextType::class, array(
          'required' => true,
          'attr' => array(
            'class' => 'form-control',
            'readonly' => 'readonly',
            'id' => 'lng'
          )
        ))
        ->add('placeId', TextType::class, array(
          'required' => true,
          'attr' => array(
            'class' => 'form-control',
            'readonly' => 'readonly',
            'id' => 'place_id'
          )
        ))
        ->add('lnAdministrativeAreaLevel1', TextType::class, array(
          'required' => false,
          'attr' => array(
            'class' => 'form-control',
            'readonly' => 'readonly',
            'id' => 'ln_administrative_area_level_1'
          )
        ))
        ->add('snAdministrativeAreaLevel1', TextType::class, array(
          'required' => false,
          'attr' => array(
            'class' => 'form-control',
            'readonly' => 'readonly',
            'id' => 'sn_administrative_area_level_1'
          )
        ))
        ->add('lnAdministrativeAreaLevel2', TextType::class, array(
          'required' => false,
          'attr' => array(
            'class' => 'form-control',
            'readonly' => 'readonly',
            'id' => 'ln_administrative_area_level_2'
          )
        ))
        ->add('snAdministrativeAreaLevel2', TextType::class, array(
          'required' => false,
          'attr' => array(
            'class' => 'form-control',
            'readonly' => 'readonly',
            'id' => 'ln_administrative_area_level_2'
          )
        ))
        ->add('lnCountry', TextType::class, array(
          'required' => false,
          'attr' => array(
            'class' => 'form-control',
//            'readonly' => 'readonly',
            'id' => 'ln_country'
          )
        ))
        ->add('snCountry', TextType::class, array(
          'required' => false,
          'attr' => array(
            'class' => 'form-control',
            'readonly' => 'readonly',
            'id' => 'sn_country'
          )
        ))
        ->add('lnLocality', TextType::class, array(
          'required' => false,
          'attr' => array(
            'class' => 'form-control',
//            'readonly' => 'readonly',
            'id' => 'ln_locality'
          )
        ))
        ->add('snLocality', TextType::class, array(
          'required' => false,
          'attr' => array(
            'class' => 'form-control',
            'readonly' => 'readonly',
            'id' => 'sn_locality'
          )
        ))
        ->add('lnPostalCode', TextType::class, array(
          'required' => false,
          'attr' => array(
            'class' => 'form-control',
            'readonly' => 'readonly',
            'id' => 'ln_postal_code'
          )
        ))
        ->add('snPostalCode', TextType::class, array(
          'required' => false,
          'attr' => array(
            'class' => 'form-control',
            'readonly' => 'readonly',
            'id' => 'ln_postal_code'
          )
        ))
        ->add('lnPremise', TextType::class, array(
          'required' => false,
          'attr' => array(
            'class' => 'form-control',
            'readonly' => 'readonly',
            'id' => 'ln_premise'
          )
        ))
        ->add('snPremise', TextType::class, array(
          'required' => false,
          'attr' => array(
            'class' => 'form-control',
            'readonly' => 'readonly',
            'id' => 'sn_premise'
          )
        ))
        ->add('lnRoute', TextType::class, array(
          'required' => false,
          'attr' => array(
            'class' => 'form-control',
            'readonly' => 'readonly',
            'id' => 'ln_route'
          )
        ))
        ->add('snRoute', TextType::class, array(
          'required' => false,
          'attr' => array(
            'class' => 'form-control',
            'readonly' => 'readonly',
            'id' => 'sn_route'
          )
        ))
        ->add('lnStreetNumber', TextType::class, array(
          'required' => false,
          'attr' => array(
            'class' => 'form-control',
            'readonly' => 'readonly',
            'id' => 'ln_street_number'
          )
        ))
        ->add('snStreetNumber', TextType::class, array(
          'required' => false,
          'attr' => array(
            'class' => 'form-control',
            'readonly' => 'readonly',
            'id' => 'sn_street_number'
          )
        ))
      ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
      $resolver->setDefaults(array(
        'data_class' => 'XS\CoreBundle\Document\GMaps'
      ));
    }

    public function getBlockPrefix()
    {
        return 'xscore_bundle_gmaps_type';
    }
}

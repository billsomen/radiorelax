<?php

namespace XS\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\IntegerToLocalizedStringTransformer;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TelephoneType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      //todo A implementer plus tard,
      //->add('country_code', 'text')
      ->add('number', IntegerType::class, array(
//        'rounding_mode' => IntegerToLocalizedStringTransformer::ROUND_CEILING,
        'required' => false,
        'attr' => array(
          'placeholder' => "Numéro de Téléphone (sans espace)"
        )
      ))
      ->add('countryCode', TextType::class, array(
        'required' => false
      ))
    ;
  }
  
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'XS\UserBundle\Document\Telephone'
    ));
  }
  
  public function getName()
  {
    return 'xsuser_bundle_telephone_type';
  }
}

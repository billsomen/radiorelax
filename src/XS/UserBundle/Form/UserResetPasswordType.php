<?php

namespace XS\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use XS\CoreBundle\Form\DocumentType;
use XS\CoreBundle\Form\LocalisationType;

class UserResetPasswordType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('username', IntegerType::class, array(
        'required' => true
      ));

  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'XS\UserBundle\Document\User'
    ));
  }

  public function getName()
  {
    return 'xsuser_bundle_user_reset_password_type';
  }
}

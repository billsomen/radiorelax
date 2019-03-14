<?php

namespace XS\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentMinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', 'text')
            ->add('photos', 'file', array(
                    'required' => false,
                    'attr' => array(
                        'multiple' => 'multiple',
                        'accept' => 'image/*'
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
        return 'xscore_bundle_comment_min_type';
    }
}

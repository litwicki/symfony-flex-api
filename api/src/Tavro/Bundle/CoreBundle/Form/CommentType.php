<?php

namespace Tavro\Bundle\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('body')
            ->add('user')
            ->add('mod', 'entity', array(
                'class' => 'Tavro\Bundle\CoreBundle\Entity\Mod',
                'mapped' => false,
            ))
            ->add('node', 'entity', array(
                'class' => 'Tavro\Bundle\CoreBundle\Entity\Node',
                'mapped' => false,
            ))
        ;
    }

    /**
     * Define the default validation groups.
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('api'),
            'csrf_protection' => false
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'comment_type';
    }
}

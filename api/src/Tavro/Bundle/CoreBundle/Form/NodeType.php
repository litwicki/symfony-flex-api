<?php

namespace Tavro\Bundle\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NodeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text')
            ->add('body', 'textarea')
            ->add('slug', 'text')
            ->add('display_date', 'date', array('required' => false))
            ->add('type', 'choice', array(
                'choices' => array(
                    'article'   => 'Article',
                    'page'      => 'Page',
                    'wiki'      => 'Wiki Entry',
                    'node'      => 'Node',
                    'guide'     => 'Guide'
                ),
                'required' => true,
            ))
            ->add('views')
            ->add('status')
            ->add('user')
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
            'data_class' => 'Tavro\Bundle\CoreBundle\Entity\Node',
            'csrf_protection' => false
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'node_type';
    }
}

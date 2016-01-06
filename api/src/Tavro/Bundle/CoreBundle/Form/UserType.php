<?php

namespace Tavro\Bundle\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('github_username')
            ->add('email')
            ->add('birthday')
            ->add('gender')
            ->add('password')
            ->add('password_token')
            ->add('password_token_expire')
            ->add('enable_notifications')
            ->add('enable_daily_digest')
            ->add('enable_private_messages')
            ->add('roles', 'entity', array(
                'class' => 'Tavro\Bundle\CoreBundle\Entity\Role',
                'multiple' => true,
                'error_bubbling' => true
            ))
            ->add('signature', 'datetime', array('required' => false))
            ->add('submit', 'submit')
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
            'data_class' => 'Tavro\Bundle\CoreBundle\Entity\User',
            'csrf_protection' => false,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'user_type';
    }
}

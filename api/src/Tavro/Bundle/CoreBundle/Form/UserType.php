<?php

namespace Tavro\Bundle\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

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
            ->add('email')
            ->add('birthday')
            ->add('gender')
            ->add('password')
            ->add('password_token')
            ->add('password_token_expire')
            ->add('roles', EntityType::class, array(
                'class' => 'Tavro\Bundle\CoreBundle\Entity\Role',
                'multiple' => true,
                'error_bubbling' => true
            ))
            ->add('signature', DateTimeType::class, array('required' => false))
            ->add('submit', SubmitType::class)
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

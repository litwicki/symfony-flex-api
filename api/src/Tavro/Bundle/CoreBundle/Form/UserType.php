<?php

namespace Tavro\Bundle\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
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
            ->add('password')
            ->add('password_token')
            ->add('password_token_expire', DateTimeType::class)
            ->add('salt')
            ->add('signature')
            ->add('last_online_date', DateTimeType::class)
            ->add('api_key')
            ->add('api_password')
            ->add('api_enabled')
            ->add('guid')
            ->add('user_ip')
            ->add('user_agent')
            ->add('body')
            ->add('status')
            ->add('roles', EntityType::class, array(
                'class' => 'TavroCoreBundle:Role',
                'choice_label' => 'Role',
                'invalid_message' => 'Please enter a valid Role',
            ))
            ->add('avatar', EntityType::class, array(
                'class' => 'TavroCoreBundle:Image',
                'choice_label' => 'Avatar',
                'invalid_message' => 'Please enter a valid Image',
            ))
            ->add('user_quickbooks', EntityType::class, array(
                'class' => 'TavroCoreBundle:UserQuickbooks',
                'choice_label' => 'Quickbooks User',
                'invalid_message' => 'Please enter a valid Quickbooks User',
            ))
            ->add('person', EntityType::class, array(
                'class' => 'TavroCoreBundle:Person',
                'choice_label' => 'Person',
                'invalid_message' => 'Please enter a valid Person',
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Tavro\Bundle\CoreBundle\Entity\User'
        ));
    }
}

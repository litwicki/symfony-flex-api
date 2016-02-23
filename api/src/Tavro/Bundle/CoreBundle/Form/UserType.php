<?php

namespace Tavro\Bundle\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

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
            ->add('password', PasswordType::class)
            ->add('password_token')
            ->add('password_token_expire', DateTimeType::class)
            ->add('salt')
            ->add('email')
            ->add('signature')
            ->add('last_online_date', DateTimeType::class)
            ->add('api_key')
            ->add('api_password')
            ->add('api_enabled')
            ->add('guid')
            ->add('user_ip')
            ->add('gender')
            ->add('user_agent')
            ->add('birthday', 'date')
            ->add('status')
            ->add('create_date', DateTimeType::class)
            ->add('update_date', DateTimeType::class)
            ->add('roles', EntityType::class, array(
                'class' => 'TavroCoreBundle:Role',
                'choice_label' => 'Role(s)'
            ))
            ->add('avatar', FileType::class)
            ->add('submit', SubmitType::class)
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

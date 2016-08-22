<?php

namespace Tavro\Bundle\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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
            ->add('signature')
            ->add('api_enabled')
            ->add('body')
            ->add('status')
            ->add('roles', EntityType::class, array(
                'multiple' => true,
                'class' => 'TavroCoreBundle:Role',
                'choice_label' => 'Role',
                'invalid_message' => 'Please enter a valid Role',
            ))
            ->add('person', EntityType::class, array(
                'class' => 'TavroCoreBundle:Person',
                'choice_label' => 'Person',
                'invalid_message' => 'Please enter a valid Person',
            ))
            ->add('submit', SubmitType::class)
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Tavro\Bundle\CoreBundle\Entity\User',
            'csrf_protection' => false
        ));
    }
}

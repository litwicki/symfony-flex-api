<?php

namespace Tavro\Bundle\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class ContactType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('job_title')
            ->add('email', EmailType::class)
            ->add('phone')
            ->add('body')
            ->add('status')
            ->add('user', EntityType::class, array(
                'class' => 'TavroCoreBundle:User',
                'choice_label' => 'User',
                'invalid_message' => 'Please enter a valid User'
            ))
            ->add('organization', EntityType::class, array(
                'class' => 'TavroCoreBundle:Organization',
                'choice_label' => 'Organization',
                'invalid_message' => 'Please enter a valid Organization'
            ))
            ->add('person', EntityType::class, array(
                'class' => 'TavroCoreBundle:Person',
                'choice_label' => 'Person',
                'invalid_message' => 'Please enter a valid Person'
            ))
            ->add('account', EntityType::class, array(
                'class' => 'TavroCoreBundle:Account',
                'choice_label' => 'Account',
                'invalid_message' => 'Please enter a valid Account'
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
            'data_class' => 'Tavro\Bundle\CoreBundle\Entity\Contact',
            'csrf_protection'   => false,
        ));
    }
}

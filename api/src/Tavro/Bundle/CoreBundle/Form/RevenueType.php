<?php

namespace Tavro\Bundle\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RevenueType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('status')
            ->add('type')
            ->add('category', EntityType::class, array(
                'class' => 'TavroCoreBundle:RevenueCategory',
                'choice_label' => 'Category',
                'invalid_message' => 'Please enter a valid Revenue Category'
            ))
            ->add('user', EntityType::class, array(
                'class' => 'TavroCoreBundle:User',
                'choice_label' => 'User',
                'invalid_message' => 'Please enter a valid User'
            ))
            ->add('customer', EntityType::class, array(
                'class' => 'TavroCoreBundle:Customer',
                'choice_label' => 'Customer',
                'invalid_message' => 'Please enter a valid Customer'
            ))
            ->add('account', EntityType::class, array(
                'class' => 'TavroCoreBundle:Account',
                'choice_label' => 'Account',
                'invalid_message' => 'Please enter a valid Account'
            ))
            ->add('services', EntityType::class, array(
                'class' => 'TavroCoreBundle:Service',
                'choice_label' => 'Services',
                'invalid_message' => 'Please enter a valid Service for this Revenue',
                'multiple' => true,
                'mapped' => false
            ))
            ->add('products', EntityType::class, array(
                'class' => 'TavroCoreBundle:Product',
                'choice_label' => 'Products',
                'invalid_message' => 'Please enter a valid Product for this Revenue',
                'multiple' => true,
                'mapped' => false
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
            'data_class' => 'Tavro\Bundle\CoreBundle\Entity\Revenue',
            'csrf_protection'   => false,
        ));
    }
}

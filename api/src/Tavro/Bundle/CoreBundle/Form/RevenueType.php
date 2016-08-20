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
                'choice_label' => 'Category'
            ))
            ->add('user', EntityType::class, array(
                'class' => 'TavroCoreBundle:User',
                'choice_label' => 'User'
            ))
            ->add('customer', EntityType::class, array(
                'class' => 'TavroCoreBundle:Customer',
                'choice_label' => 'Customer'
            ))
            ->add('organization', EntityType::class, array(
                'class' => 'TavroCoreBundle:Organization',
                'choice_label' => 'Organization'
            ))
            ->add('services', EntityType::class, array(
                'class' => 'TavroCoreBundle:Service',
                'choice_label' => 'Services',
                'multiple' => true,
                'mapped' => false
            ))
            ->add('products', EntityType::class, array(
                'class' => 'TavroCoreBundle:Product',
                'choice_label' => 'Products',
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

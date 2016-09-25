<?php

namespace Tavro\Bundle\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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
            ->add('status', IntegerType::class, [
                'invalid_message' => 'Invalid status, only 0, 1, 2 allowed'
            ])
            ->add('type')
            ->add('category', EntityType::class, [
                'class' => 'TavroCoreBundle:RevenueCategory',
                'choice_label' => 'Category',
                'invalid_message' => 'Please enter a valid Revenue Category'
            ])
            ->add('user', EntityType::class, [
                'class' => 'TavroCoreBundle:User',
                'choice_label' => 'User',
                'invalid_message' => 'Please enter a valid User'
            ])
            ->add('account', EntityType::class, [
                'class' => 'TavroCoreBundle:Account',
                'choice_label' => 'Account',
                'invalid_message' => 'Please enter a valid Account'
            ])
            ->add('services', EntityType::class, [
                'class' => 'TavroCoreBundle:Service',
                'choice_label' => 'Services',
                'invalid_message' => 'Please enter a valid Service for this Revenue',
                'multiple' => true,
                'mapped' => false
            ])
            ->add('products', EntityType::class, [
                'class' => 'TavroCoreBundle:Product',
                'choice_label' => 'Products',
                'invalid_message' => 'Please enter a valid Product for this Revenue',
                'multiple' => true,
                'mapped' => false
            ])
            ->add('submit', SubmitType::class)
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Tavro\Bundle\CoreBundle\Entity\Revenue',
            'csrf_protection'   => false,
        ]);
    }
}

<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RevenueProductType extends AbstractType
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
            ->add('product', EntityType::class, [
                'class' => 'TavroCoreBundle:Product',
                'choice_label' => 'Product',
                'invalid_message' => 'Please enter a valid Product for this Revenue Product'
            ])
            ->add('revenue', EntityType::class, [
                'class' => 'TavroCoreBundle:Revenue',
                'choice_label' => 'Revenue',
                'invalid_message' => 'Please enter a valid Revenue for this Revenue Product'
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
            'data_class' => 'Tavro\Entity\RevenueProduct',
            'csrf_protection'   => false,
        ]);
    }
}

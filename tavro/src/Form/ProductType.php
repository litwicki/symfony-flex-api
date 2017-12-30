<?php

namespace Tavro\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\DBAL\Types\StringType;

class ProductType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('body')
            ->add('name')
            ->add('status', IntegerType::class, [
                'invalid_message' => 'Invalid status, only 0, 1, 2 allowed'
            ])
            ->add('cost', MoneyType::class, [
                'required' => true,
                'invalid_message' => 'Invalid `cost` please enter a valid number'
            ])
            ->add('price', MoneyType::class, [
                'required' => true,
                'invalid_message' => 'Invalid `price` please enter a valid number'
            ])
            ->add('category', EntityType::class, [
                'required' => true,
                'class' => 'TavroCoreBundle:ProductCategory',
                'choice_label' => 'Category',
                'invalid_message' => 'Please enter a valid Product Category'
            ])
            ->add('account', EntityType::class, [
                'required' => true,
                'class' => 'TavroCoreBundle:Account',
                'choice_label' => 'Account',
                'invalid_message' => 'Please enter a valid Account'
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
            'data_class' => 'Tavro\Entity\Product',
            'csrf_protection' => false,
        ]);
    }
}

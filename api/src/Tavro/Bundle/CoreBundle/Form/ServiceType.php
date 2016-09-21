<?php

namespace Tavro\Bundle\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ServiceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('body')
            ->add('title')
            ->add('price')
            ->add('type')
            ->add('status', IntegerType::class, [
                'invalid_message' => 'Invalid status, only 0, 1, 2 allowed'
            ])
            ->add('category', EntityType::class, [
                'class' => 'TavroCoreBundle:ServiceCategory',
                'choice_label' => 'Organization',
                'invalid_message' => 'Please enter a valid Service Category'
            ])
            ->add('organization', EntityType::class, [
                'class' => 'TavroCoreBundle:Organization',
                'choice_label' => 'Organization',
                'invalid_message' => 'Please enter a valid Organization'
            ])
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Tavro\Bundle\CoreBundle\Entity\Service',
            'csrf_protection' => false
        ]);
    }
}

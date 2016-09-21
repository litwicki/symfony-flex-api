<?php

namespace Tavro\Bundle\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RevenueServiceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('status')
            ->add('service', EntityType::class, array(
                'class' => 'TavroCoreBundle:Service',
                'choice_label' => 'Service',
                'invalid_message' => 'Please enter a valid Service for this Revenue Service'
            ))
            ->add('revenue', EntityType::class, array(
                'class' => 'TavroCoreBundle:Revenue',
                'choice_label' => 'Revenue',
                'invalid_message' => 'Please enter a valid Revenue for this Revenue Service'
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
            'data_class' => 'Tavro\Bundle\CoreBundle\Entity\RevenueService',
            'csrf_protection'   => false,
        ));
    }
}

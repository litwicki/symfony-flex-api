<?php

namespace Tavro\Bundle\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class CustomerType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('job_title')
            ->add('status', IntegerType::class, [
                'invalid_message' => 'Invalid status, only 0, 1, 2 allowed'
            ])
            ->add('user', EntityType::class, [
                'class' => 'TavroCoreBundle:User',
                'choice_label' => 'User',
                'invalid_message' => 'Please enter a valid User'
            ])
            ->add('person', EntityType::class, [
                'class' => 'TavroCoreBundle:Person',
                'choice_label' => 'Person',
                'required' => true,
                'invalid_message' => 'Please enter a valid Person'
            ])
            ->add('organization', EntityType::class, [
                'class' => 'TavroCoreBundle:Organization',
                'choice_label' => 'Organization',
                'required' => true,
                'invalid_message' => 'Please enter a valid Organization'
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
            'data_class' => 'Tavro\Bundle\CoreBundle\Entity\Customer',
            'csrf_protection' => false,
        ]);
    }
}

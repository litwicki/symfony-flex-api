<?php

namespace Tavro\Bundle\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MetricType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('calculator')
            ->add('body', TextareaType::class, [
                'label' => 'Notes',
                'attr' => ['class' => 'wysiwyg']
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Disabled' => 0,
                    'Active' => 1,
                    'Pending' => 2,
                    'Processing' => 3,
                ],
                'invalid_message' => 'Invalid status, only 0, 1, 2 allowed'
            ])
            ->add('account', EntityType::class, [
                'class' => 'TavroCoreBundle:Account',
                'choice_label' => 'Account',
                'invalid_message' => 'Please enter a valid Account'
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
                'label' => 'Save'
            ])
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Tavro\Bundle\CoreBundle\Entity\Metric'
        ));
    }
}

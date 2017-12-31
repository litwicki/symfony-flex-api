<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('signature', TextareaType::class, [
                'attr' => ['class' => 'wysiwyg']
            ])
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
            ->add('roles', null, [
                'attr' => ['class' => 'chosen'],
                'multiple' => TRUE
            ])
            ->add('person', null, [
                'attr' => ['class' => 'chosen']
            ])
            ->add('api_enabled')
            ->add('password')
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
        $resolver->setDefaults([
            'data_class' => 'Tavro\Entity\User',
            'csrf_protection' => false
        ]);
    }
}

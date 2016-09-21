<?php

namespace Tavro\Bundle\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class PersonType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('first_name')
            ->add('middle_name')
            ->add('last_name')
            ->add('suffix')
            ->add('gender', ChoiceType::class, [
                'choices' => array(
                    'male' => 'Male',
                    'female' => 'Female'
                ),
            ])
            ->add('birthday', DateType::class, [
                'invalid_message' => 'Please enter a valid date birthday: Y-m-d',
                'widget' => 'single_text',
                'format' => 'Y-m-d',
            ])
            ->add('address')
            ->add('address2')
            ->add('city')
            ->add('state')
            ->add('zip', IntegerType::class, [
                'invalid_message' => 'Please enter a valid integer postal code'
            ])
            ->add('email', EmailType::class, [
                'invalid_message' => 'Invalid email address'
            ])
            ->add('phone')
            ->add('body')
            ->add('status', IntegerType::class, [
                'invalid_message' => 'Invalid status, only 0, 1, 2 allowed'
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
            'data_class' => 'Tavro\Bundle\CoreBundle\Entity\Person',
            'csrf_protection' => false
        ]);
    }
}

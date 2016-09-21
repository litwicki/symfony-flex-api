<?php

namespace Tavro\Bundle\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class NodeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type')
            ->add('body')
            ->add('display_date', DateTimeType::class, [
                'invalid_message' => 'Please enter a valid date with time for display date: Y-m-d h:i:s',
                'widget' => 'single_text',
                'format' => 'Y-m-d h:i:s',
            ])
            ->add('views')
            ->add('title')
            ->add('status', IntegerType::class, [
                'invalid_message' => 'Invalid status, only 0, 1, 2 allowed'
            ])
            ->add('user', EntityType::class, [
                'class' => 'TavroCoreBundle:User',
                'choice_label' => 'User',
                'invalid_message' => 'Please enter a valid User',
            ])
            ->add('account', EntityType::class, [
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
            'data_class' => 'Tavro\Bundle\CoreBundle\Entity\Node',
            'csrf_protection'   => false,
        ]);
    }
}

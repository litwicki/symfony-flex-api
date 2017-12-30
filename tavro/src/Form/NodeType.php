<?php

namespace Tavro\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            ->add('type', ChoiceType::class, [
                'choices' => array(
                    'Article' => 'article',
                    'Node' => 'node',
                    'Page' => 'page',
                    'Press Release' => 'press',
                    'Wiki'  => 'wiki'
                ),
                'invalid_message' => 'Please enter a valid Node type: article, page, press, node'
            ])
            ->add('body')
            ->add('display_date', DateTimeType::class, [
                'invalid_message' => 'Please enter a valid date with time for display date: Y-m-d h:i:s',
                'widget' => 'single_text',
                'format' => 'Y-m-d h:i:s',
            ])
            ->add('views', IntegerType::class, [
                'invalid_message' => 'Views must be a valid integer'
            ])
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
            'data_class' => 'Tavro\Entity\Node',
            'csrf_protection'   => false,
        ]);
    }
}

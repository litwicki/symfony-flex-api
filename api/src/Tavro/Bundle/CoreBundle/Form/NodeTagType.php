<?php

namespace Tavro\Bundle\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class NodeTagType extends AbstractType
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
            ->add('tag', EntityType::class, [
                'class' => 'TavroCoreBundle:Tag',
                'choice_label' => 'Tag',
                'invalid_message' => 'Please enter a valid Tag for this Node'
            ])
            ->add('node', EntityType::class, [
                'class' => 'TavroCoreBundle:Node',
                'choice_label' => 'Node',
                'invalid_message' => 'Please enter a valid Node for this Tag'
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
            'data_class' => 'Tavro\Bundle\CoreBundle\Entity\NodeTag',
            'csrf_protection'   => false,
        ]);
    }
}

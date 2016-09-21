<?php

namespace Tavro\Bundle\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class OrganizationCommentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('status')
            ->add('comment', EntityType::class, array(
                'class' => 'TavroCoreBundle:Comment',
                'choice_label' => 'Comment',
                'invalid_message' => 'Please enter a valid Comment'
            ))
            ->add('customer', EntityType::class, array(
                'class' => 'TavroCoreBundle:Customer',
                'choice_label' => 'Customer',
                'invalid_message' => 'Please enter a valid Customer'
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
            'data_class' => 'Tavro\Bundle\CoreBundle\Entity\OrganizationComment',
            'csrf_protection'   => false,
        ));
    }
}

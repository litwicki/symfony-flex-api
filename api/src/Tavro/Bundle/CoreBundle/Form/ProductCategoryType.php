<?php

namespace Tavro\Bundle\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProductCategoryType extends AbstractType
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
            ->add('slug')
            ->add('status')
            ->add('create_date', DateTimeType::class)
            ->add('update_date', DateTimeType::class)
            ->add('organization', EntityType::class, array(
                'class' => 'TavroCoreBundle:Organization',
                'choice_label' => 'Organization'
            ))
            ->add('updated_by', EntityType::class, array(
                'class' => 'TavroCoreBundle:User',
                'choice_label' => 'User'
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
            'data_class' => 'Tavro\Bundle\CoreBundle\Entity\ProductCategory'
        ));
    }
}

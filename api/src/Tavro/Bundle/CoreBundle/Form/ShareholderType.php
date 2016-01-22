<?php

namespace Tavro\Bundle\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ShareholderType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('first_name')
            ->add('last_name')
            ->add('address')
            ->add('address2')
            ->add('city')
            ->add('state')
            ->add('zip')
            ->add('email')
            ->add('shares')
            ->add('phone')
            ->add('notes')
            ->add('status')
            ->add('user', EntityType::class, array(
                'class' => 'TavroCoreBundle:User',
                'choice_label' => 'User'
            ))
            ->add('create_date', DateTimeType::class)
            ->add('update_date', DateTimeType::class)
            ->add('submit', SubmitType::class)
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Tavro\Bundle\CoreBundle\Entity\Shareholder'
        ));
    }
}

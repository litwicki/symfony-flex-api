<?php

namespace Tavro\Bundle\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ForecastRevenueType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('revenue_date', DateTimeType::class, [
                'invalid_message' => 'Please enter a valid revenue date:  Y-m-d',
                'widget' => 'single_text',
                'format' => 'Y-m-d',
            ])
            ->add('amount', IntegerType::class, [
                'invalid_message' => 'Please enter a valid integer revenue amount.'
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
            ->add('create_date', DateTimeType::class, [
                'invalid_message' => 'Please enter a valid create date:  Y-m-d',
                'widget' => 'single_text',
                'format' => 'Y-m-d',
            ])
            ->add('update_date', DateTimeType::class, [
                'invalid_message' => 'Please enter a valid update date:  Y-m-d',
                'widget' => 'single_text',
                'format' => 'Y-m-d',
            ])
            ->add('organization')
            ->add('category', null, [
                'choice_label' => 'Category',
                'invalid_message' => 'Please enter a valid Category',
                'attr' => ['class' => 'chosen']
            ])
            ->add('user')
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Tavro\Bundle\CoreBundle\Entity\ForecastRevenue',
            'csrf_protection' => false
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'tavro_forecast_revenue';
    }


}

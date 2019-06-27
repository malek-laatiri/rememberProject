<?php

namespace App\Form;

use App\Entity\Remainder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RemainderType
 * @package App\Form
 */
class RemainderType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('remember_date');

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Remainder::class,
            'allow_add' => true,
            'multiple'=>true,'prototype' => true,
            'prototype_data' => 'New Tag Placeholder',

        ]);
    }
}

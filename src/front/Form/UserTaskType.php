<?php

namespace App\front\Form;

use App\common\Entity\UserTask;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserTaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('is_approved')
            ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserTask::class,
            'allow_add' => true,
            'multiple'=>true,'
            prototype' => true,
            'prototype_data' => 'New Tag Placeholder',

        ]);
    }
}

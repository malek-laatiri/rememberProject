<?php

namespace App\Form;

use App\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('description', TextType::class)
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('starthour', TimeType::class)
            ->add('endhour', TimeType::class)
            ->add('endhour', TimeType::class)
            ->add('remainders', CollectionType::class, [
                'entry_type' => RemainderType::class,
                'prototype' => true,
                'allow_add' => true,

            ])
            ->add('userTasks', CollectionType::class, [
                'entry_type' => UserTaskType::class,
                'prototype' => true,
                'allow_add' => true,

            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
            "allow_extra_fields" => true
        ]);
    }
}

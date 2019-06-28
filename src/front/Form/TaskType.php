<?php

namespace App\front\Form;

use App\common\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;


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
            ->add('starthour', DateTimeType::class)
            ->add('endhour', DateTimeType::class)

            ->add('remainders',CollectionType::class, [
                'entry_type' => RemainderType::class,
                'allow_add' => true,
                'prototype' => true,
                'by_reference' => false])
            ->add('userTasks',CollectionType::class, [
                'entry_type' => UserTaskType::class,
                'allow_add' => true,
                'prototype' => true,
                'by_reference' => false])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
            "allow_extra_fields" => true
        ]);
    }
}

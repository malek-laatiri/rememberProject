<?php

namespace App\Front\Form;

use App\Common\Entity\Task;
use App\Common\Entity\UserTask;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;



class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('title', TextType::class)
            ->add('description', TextAreaType::class)
            ->add('startHour', DateTimeType::class,[
                'widget' => 'choice',
                'years'=>range(date('y'),date('y')+50),
                'months'=>range(date('m'),12),
                'days'=>range(date('d'),31)])
            ->add('endHour', DateTimeType::class,[
                'widget' => 'choice',
                'years'=>range(date('y'),date('y')+50),
                'months'=>range(date('m'),12),
                'days'=>range(date('d'),31)])
            ->add('remainders', CollectionType::class, [
                'entry_type' => RemainderType::class,
                'allow_add' => true,
                'prototype' => true,
                'by_reference' => false,
                'label' => false])
            ->add('userTasks', CollectionType::class, [
                'entry_type' => UserTaskType::class,
                'allow_add' => true,
                'prototype' => true,
                'by_reference' => false,
                'label' => false])
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {

                $form = $event->getForm();
                /** @var Task $task */
                $task = $form->getData();

                $userTask = new UserTask();
                $userTask
                    ->setIsCreator(true)
                    ->setTask($task)
                    ->setIsApproved(true)
                    ->setUser($currentUser=$form->getConfig()->getOption('user'));

                $task->addUserTask($userTask);


            });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
            "allow_extra_fields" => true,
            'user'=>null
        ]);
    }
}

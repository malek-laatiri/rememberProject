<?php

namespace App\Backoffice\Form;

use App\Common\Entity\Task;
use App\Common\Entity\UserTask;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class TaskType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('title', TextType::class)
            ->add('description', TextType::class)
            ->add('startHour', DateTimeType::class)
            ->add('endHour', DateTimeType::class)
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {

                $form = $event->getForm();
                /** @var Task $task */
                $task = $form->getData();

                /** @var UserTask $userTask */
                $userTask = new UserTask();
                $userTask
                    ->setIsCreator(true)
                    ->setTask($task)
                    ->setIsApproved(true)
                    ->setUser($currentUser=$form->getConfig()->getOption('user'));



                $task->addUserTask($userTask);


            });

    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
            "allow_extra_fields" => true,
                        'user'=>null

        ]);
    }
}

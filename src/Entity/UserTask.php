<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserTaskRepository")
 */
class UserTask
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="userTasks",cascade={"remove"})
     *
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Task", inversedBy="userTasks",cascade={"remove"})
     *
     */
    private $task;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_creator;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_approved;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getTask(): ?task
    {
        return $this->task;
    }

    public function setTask(?task $task): self
    {
        $this->task = $task;

        return $this;
    }

    public function getIsCreator(): ?bool
    {
        return $this->is_creator;
    }

    public function setIsCreator(bool $is_creator): self
    {
        $this->is_creator = $is_creator;

        return $this;
    }

    public function getIsApproved(): ?bool
    {
        return $this->is_approved;
    }

    public function setIsApproved(bool $is_approved): self
    {
        $this->is_approved = $is_approved;

        return $this;
    }

}

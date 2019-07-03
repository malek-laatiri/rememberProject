<?php

namespace App\Common\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="userTasks")
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
    private $isCreator;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isApproved;


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
        return $this->isCreator;
    }

    public function setIsCreator(bool $is_creator): self
    {
        $this->isCreator = $is_creator;

        return $this;
    }

    public function getIsApproved(): ?bool
    {
        return $this->isApproved;
    }

    public function setIsApproved(bool $is_approved): self
    {
        $this->isApproved = $is_approved;

        return $this;
    }

    public function __construct()
    {
        $this->setIsCreator(false);

    }


}

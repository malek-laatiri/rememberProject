<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TaskRepository")
 */

class Task
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="datetime")
     */
    private $starthour;

    /**
     * @ORM\Column(type="datetime")
     */
    private $endhour;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdat;


    /**
     * @ORM\OneToMany(targetEntity="UserTask", mappedBy="task", cascade={"persist","remove"})
     */
    private $userTasks;

    /**
     * @ORM\OneToMany(targetEntity="Remainder", mappedBy="task", cascade={"persist","remove"})
     */
    private $remainders;


    public function __construct()
    {
        $this->userTasks = new ArrayCollection();
        $this->createdat = new \DateTime();
        $this->remainders = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getStarthour(): ?\DateTimeInterface
    {
        return $this->starthour;
    }

    public function setStarthour(\DateTimeInterface $starthour): self
    {
        $this->starthour = $starthour;

        return $this;
    }

    public function getEndhour(): ?\DateTimeInterface
    {
        return $this->endhour;
    }

    public function setEndhour(\DateTimeInterface $endhour): self
    {
        $this->endhour = $endhour;

        return $this;
    }

    public function getCreatedat(): ?\DateTimeInterface
    {
        return $this->createdat;
    }

    public function setCreatedat(\DateTimeInterface $createdat): self
    {
        $this->createdat = $createdat;

        return $this;
    }

    /**
     * @return Collection|UserTask[]
     */
    public function getUserTasks(): Collection
    {
        return $this->userTasks;
    }

    public function addUserTask(UserTask $userTask): self
    {
        if (!$this->userTasks->contains($userTask)) {
            $this->userTasks[] = $userTask;
            $userTask->setTask($this);
        }

        return $this;
    }

    public function removeUserTask(UserTask $userTask): self
    {
        if ($this->userTasks->contains($userTask)) {
            $this->userTasks->removeElement($userTask);
            // set the owning side to null (unless already changed)
            if ($userTask->getTask() === $this) {
                $userTask->setTask(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Remainder[]
     */
    public function getRemainders(): Collection
    {
        return $this->remainders;
    }

    /**
     * @param Remainder $remainder
     * @return Task
     */
    public function addRemainder(Remainder $remainder): self
    {
        if (!$this->remainders->contains($remainder)) {
            $this->remainders[] = $remainder;
            $remainder->setTask($this);
        }

        return $this;
    }

    /**
     * @param Remainder $remainder
     * @return Task
     */
    public function removeRemainder(Remainder $remainder): self
    {
        if ($this->remainders->contains($remainder)) {
            $this->remainders->removeElement($remainder);
            // set the owning side to null (unless already changed)
            if ($remainder->getTask() === $this) {
                $remainder->setTask(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->title;
    }
}

<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Remainder
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\RemainderRepository")
 */
class Remainder
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $remember_date;

    /**
     * @ORM\ManyToOne(targetEntity="Task", inversedBy="remainders", cascade={"remove"})
     */
    private $task;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRememberDate(): ?\DateTimeInterface
    {
        return $this->remember_date;
    }

    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function setTask(?Task $task): self
    {
        $this->task = $task;

        return $this;
    }

    /**
     * @param mixed $remember_date
     */
    public function setRememberDate($remember_date): void
    {
        $this->remember_date = $remember_date;
    }

    public function __toString()
    {
        $date = $this->remember_date;
        $result = date_format($date,"Y/m/d H:i:s");
        return $result;

     }


}

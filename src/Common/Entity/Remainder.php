<?php

namespace App\Common\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
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
    private $rememberDate;

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
        return $this->rememberDate;
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
        $this->rememberDate = $remember_date;
    }

    public function __toString()
    {
        $date = $this->rememberDate;
        return date_format($date,"Y/m/d H:i:s");

     }


}

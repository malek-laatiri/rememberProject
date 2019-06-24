<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;



/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;



    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserTask", mappedBy="user")
     */
    private $userTasks;



    public function __construct()
    {
        parent::__construct();
        $this->userTasks = new ArrayCollection();

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
            $userTask->setUser($this);
        }

        return $this;
    }

    public function removeUserTask(UserTask $userTask): self
    {
        if ($this->userTasks->contains($userTask)) {
            $this->userTasks->removeElement($userTask);
            // set the owning side to null (unless already changed)
            if ($userTask->getUser() === $this) {
                $userTask->setUser(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
return $this->email;    }


}

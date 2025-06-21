<?php

namespace App\Entity;

use App\Repository\ManagerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ManagerRepository::class)]
class Manager extends User
{   

    
      #[ORM\ManyToOne(inversedBy: 'managers')]
      private ?User $user = null;

      public function __construct()
    {
        return $this->roles[]="ROLE_MANAGER";
    
    }


     public function getUser(): ?User
     {
         return $this->user;
     }

     public function setUser(?User $user): static
     {
         $this->user = $user;

         return $this;
     }


}

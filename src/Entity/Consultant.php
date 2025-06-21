<?php

namespace App\Entity;

use App\Repository\ConsultantRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConsultantRepository::class)]
class Consultant extends User
{
     #[ORM\ManyToOne(inversedBy: 'consultants')]
     private ?User $user = null;

     public function __construct()
    {
        return $this->roles[]="ROLE_CONSULTANT";
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

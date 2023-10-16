<?php

namespace App\Entity;

use App\Repository\EntrepriseGhaithRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EntrepriseGhaithRepository::class)]
class EntrepriseGhaith
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}

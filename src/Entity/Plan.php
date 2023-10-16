<?php

namespace App\Entity;

use App\Repository\PlanRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;


#[ORM\Entity(repositoryClass: PlanRepository::class)]
/**
 * @ORM\Entity
 * @Gedmo\Loggable
 */
class Plan
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("plans")]
    private ?int $id = null;

     /**
     * @Assert\NotBlank(message=" Nom doit etre non vide")
     * 
     *
     */

    #[ORM\Column(length: 255)]
    #[Groups("plans")]
    private ?string $nom = null;


   /**
     * @Assert\NotBlank(message="description  doit etre non vide")
     * @Assert\Length(
     *      
     *      max = 300,
     *      
     *      maxMessage = "description ne depasse pas 300 mots" )
     * @ORM\Column(type="string", length=1000)
     */

    #[ORM\Column(type: Types::TEXT)]
    #[Groups("plans")]
    private ?string $description = null;

    /**
     * @Assert\Positive(message="La valeur doit être un entier positif.")
     * @Assert\NotBlank(message=" Ce champ doit etre non vide")
     * @Assert\Range(
     *      min = 1,
     *      max = 50,
     *      notInRangeMessage = "La valeur doit être comprise entre {{ min }} et {{ max }}.",
     *      invalidMessage = "La valeur doit être un entier."
     * )
     */

    #[ORM\Column]
    #[Groups("plans")]
    public ?int $nombre_de_seances = null;

    #[ORM\ManyToOne(inversedBy: 'plan')]
    #[Groups("plans")]
    private ?Coach $coaches = null;

    #[ORM\Column]
    #[Groups("plans")]
    private ?int $nbParticipant = null;

    #[ORM\OneToMany(mappedBy: 'Plan', targetEntity: Reservation::class)]
    #[Groups("plans")]
    private Collection $reservations;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

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

    public function getNombreDeSeances(): ?int
    {
        return $this->nombre_de_seances;
    }

    public function setNombreDeSeances(int $nombre_de_seances): self
    {
        $this->nombre_de_seances = $nombre_de_seances;

        return $this;
    }

    public function getCoaches(): ?Coach
    {
        return $this->coaches;
    }

    public function setCoaches(?Coach $coaches): self
    {
        $this->coaches = $coaches;

        return $this;
    }

    public function getNbParticipant(): ?int
    {
        return $this->nbParticipant;
    }

    public function setNbParticipant(int $nbParticipant): self
    {
        $this->nbParticipant = $nbParticipant;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setPlan($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getPlan() === $this) {
                $reservation->setPlan(null);
            }
        }

        return $this;
    }
}


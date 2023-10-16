<?php

namespace App\Entity;
    
use App\Repository\CoachRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Gedmo\Mapping\Annotation\Decimal;
use Gedmo\Mapping\Annotation as Gedmo;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
#[ORM\Entity(repositoryClass: CoachRepository::class)]
/**
*@ ApiResource(formats={"json"})
*@ ORM\Entity(repositoryClass=CoachRepository::class)
*@ Vich\Uploadable
*/
class Coach
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("coachs")]
    private ?int $id = null;


    /**
     * @Assert\NotBlank(message="Nom  doit etre non vide")
     */

    #[ORM\Column(length: 255)]
    #[Groups("coachs")]
    private ?string $Nom = null;

    /**
     * @Assert\NotBlank(message="Prenom  doit etre non vide")
     */

    #[ORM\Column(length: 255)]
    #[Groups("coachs")]
    private ?string $Prenom = null;

    /**
     * @Assert\Positive(message="La valeur doit être un entier positif.")
     * @Assert\NotBlank(message=" Ce champ doit etre non vide")
     * @ORM\Column(type="decimal", precision=5, scale=2)
     * @Assert\Range(
     *      min = 18,
     *      max = 50,
     *      notInRangeMessage = "La valeur doit être comprise entre {{ min }} et {{ max }} ans.",
     *      invalidMessage = "La valeur doit être un entier."
     * )
     */
    #[ORM\Column(length: 255)]
    #[Groups("coachs")]
    private ?int $Age = null;


    /**
     * @Assert\NotBlank(message="Specialité doit etre non vide")
     * @Assert\Length(
     *      
     *      max = 30,
     *      
     *      maxMessage = "cette case  ne depasse pas 30 mots" )
     * @ORM\Column(type="string", length=1000)
     */
    #[ORM\Column(length: 255)]
    #[Groups("coachs")]
    private ?string $specialite = null;


    /**
     * @Assert\Email(
     *     message = "L'e-mail '{{ value }}' n'est pas valide."
     * )
     * @Assert\NotBlank(message="L'email  doit etre non vide")
     * @Assert\Regex(
     *     pattern="/@/",
     *     message="L'e-mail doit contenir un symbole @."
     * )
     */
    #[ORM\Column(length: 255)]
    #[Groups("coachs")]
    private ?string $email = null;

  

    

    #[ORM\OneToMany(mappedBy: 'coches', targetEntity: Plan::class)]
    private Collection $plans;

    #[ORM\OneToMany(mappedBy: 'coaches', targetEntity: Plan::class)]
    private Collection $plan;

    public function __construct()
    {
        $this->plans = new ArrayCollection();
        $this->plan = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(string $Prenom): self
    {
        $this->Prenom = $Prenom;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->Age;
    }

    public function setAge(int $Age): self
    {
        $this->Age = $Age;

        return $this;
    }

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(string $specialite): self
    {
        $this->specialite = $specialite;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }


    

  
    

    

    /**
     * @return Collection<int, Plan>
     */
    public function getPlans(): Collection
    {
        return $this->plans;
    }

    public function addPlan(Plan $plan): self
    {
        if (!$this->plans->contains($plan)) {
            $this->plans->add($plan);
            $plan->setCoches($this);
        }

        return $this;
    }

    public function removePlan(Plan $plan): self
    {
        if ($this->plans->removeElement($plan)) {
            // set the owning side to null (unless already changed)
            if ($plan->getCoches() === $this) {
                $plan->setCoches(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Plan>
     */
    public function getPlan(): Collection
    {
        return $this->plan;
    }
}

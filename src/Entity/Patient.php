<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
class Patient extends User
{
    #[ORM\Column(type: 'string')]
    private string $nom;

    #[ORM\Column(type: 'string')]
    private string $prenom;

    #[ORM\Column(type: 'string', unique: true)]
    private string $code;

    #[ORM\Column(type: 'json')]
    private array $antecedentsMedicaux = [];

    #[ORM\OneToMany(mappedBy: 'patient', targetEntity: Consultation::class)]
    private $consultations;

    #[ORM\OneToMany(mappedBy: 'patient', targetEntity: Prestation::class)]
    private $prestations;

    #[ORM\OneToMany(mappedBy: 'patient', targetEntity: RendezVous::class)]
    private $rendezVous;

    public function __construct()
    {
        $this->consultations = new ArrayCollection();
        $this->prestations = new ArrayCollection();
        $this->rendezVous = new ArrayCollection();
    }
    public function getNom(): string
    {
        return $this->nom;
    }
    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }
    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;
        return $this;
    }
    public function getCode(): string
    {
        return $this->code;
    }
    public function setCode(string $code): self
    {
        $this->code = $code;
        return $this;
    }
    public function getAntecedentsMedicaux(): array
    {
        return $this->antecedentsMedicaux;
    }
    public function setAntecedentsMedicaux(array $antecedentsMedicaux): self
    {
        $this->antecedentsMedicaux = $antecedentsMedicaux;
        return $this;
    }
    public function getConsultations()
    {
        return $this->consultations;
    }
    
 
}
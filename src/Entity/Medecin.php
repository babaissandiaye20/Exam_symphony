<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
class Medecin extends User
{
    #[ORM\Column(type: 'string')]
    private string $nom;

    #[ORM\Column(type: 'string')]
    private string $prenom;

    #[ORM\Column(type: 'string')]
    private string $specialite;

    #[ORM\OneToMany(mappedBy: 'medecin', targetEntity: Consultation::class)]
    private $consultations;

    #[ORM\OneToMany(mappedBy: 'medecin', targetEntity: RendezVous::class)]
    private $rendezVous;

    public function __construct()
    {
        $this->consultations = new ArrayCollection();
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
    public function getSpecialite(): string
    {
        return $this->specialite;
    }
    public function setSpecialite(string $specialite): self
    {
        $this->specialite = $specialite;
        return $this;
    }
    public function getConsultations()
    {
        return $this->consultations;
    }
  
}

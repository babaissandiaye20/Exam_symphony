<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
class Consultation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $date;

    #[ORM\Column(type: 'json')]
    private array $constantes = []; // Ex: température, tension...

    #[ORM\Column(type: 'string')]
    private string $statut;

    #[ORM\ManyToOne(targetEntity: Patient::class, inversedBy: 'consultations')]
    private Patient $patient;

    #[ORM\ManyToOne(targetEntity: Medecin::class, inversedBy: 'consultations')]
    private Medecin $medecin;

    #[ORM\OneToOne(mappedBy: 'consultation', targetEntity: Ordonnance::class, cascade: ['persist', 'remove'])]
    private ?Ordonnance $ordonnance = null;

    #[ORM\ManyToMany(targetEntity: Prestation::class)]
    #[ORM\JoinTable(name: 'consultation_prestation')]
    private $prestations;

    public function __construct()
    {
        $this->prestations = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }
    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }
    public function getConstantes(): array
    {
        return $this->constantes;
    }
    public function setConstantes(array $constantes): self
    {
        $this->constantes = $constantes;
        return $this;
    }
    public function getStatut(): string
    {
        return $this->statut;
    }
    public function setStatut(string $statut): self
    {
        $this->statut = $statut;
        return $this;
    }
    public function getPatient(): Patient
    {
        return $this->patient;
    }
    public function setPatient(Patient $patient): self
    {
        $this->patient = $patient;
        return $this;
    }
    public function getMedecin(): Medecin
    {
        return $this->medecin;
    }
    public function setMedecin(Medecin $medecin): self
    {
        $this->medecin = $medecin;
        return $this;
    }
    public function getOrdonnance(): ?Ordonnance
    {
        return $this->ordonnance;
    }
}

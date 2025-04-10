<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class RendezVous
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $date;

    #[ORM\Column(type: 'string')]
    private string $type; // consultation ou prestation

    #[ORM\Column(type: 'string')]
    private string $statut;

    #[ORM\ManyToOne(targetEntity: Patient::class, inversedBy: 'rendezVous')]
    private Patient $patient;

    #[ORM\ManyToOne(targetEntity: Medecin::class, inversedBy: 'rendezVous')]
    private ?Medecin $medecin = null;

    #[ORM\ManyToOne(targetEntity: Prestation::class)]
    private ?Prestation $prestation = null;

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
    public function getType(): string
    {
        return $this->type;
    }
    public function setType(string $type): self
    {
        $this->type = $type;
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
    //setPatient
    public function setPatient(Patient $patient): self
    {
        $this->patient = $patient;
        return $this;
    }
    public function getMedecin(): ?Medecin
    {
        return $this->medecin;
    }
    public function setMedecin(?Medecin $medecin): self
    {
        $this->medecin = $medecin;
        return $this;
    }
    public function getPrestation(): ?Prestation
    {
        return $this->prestation;
    }
    public function setPrestation(?Prestation $prestation): self
    {
        $this->prestation = $prestation;
        return $this;
    }

}

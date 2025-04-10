<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
class Ordonnance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'ordonnance', targetEntity: Consultation::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Consultation $consultation;

    #[ORM\OneToMany(mappedBy: 'ordonnance', targetEntity: OrdonnanceMedicament::class, cascade: ['persist', 'remove'])]
    private $medicaments;

    public function __construct()
    {
        $this->medicaments = new ArrayCollection();
    }
}


<?php
namespace App\DataFixtures;

use App\Entity\Patient;
use App\Entity\Medecin;
use App\Entity\Consultation;
use App\Entity\Prestation;
use App\Entity\RendezVous;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
private $hasher;

public function __construct(UserPasswordHasherInterface $hasher)
{
$this->hasher = $hasher;
}

public function load(ObjectManager $manager): void
{
// === Créer un patient ===
$patient = new Patient();
$patient->setEmail("patient@aro.com");
$patient->setNom("Sow");
$patient->setPrenom("Awa");
$patient->setCode("P001");
$patient->setAntecedentsMedicaux(["Diabète", "Hypertension"]);
$patient->setRoles(['ROLE_PATIENT']);
$patient->setPassword($this->hasher->hashPassword($patient, 'passer123'));
$manager->persist($patient);

// === Créer un médecin ===
$medecin = new Medecin();
$medecin->setEmail("medecin@aro.com");
$medecin->setNom("Fall");
$medecin->setPrenom("Cheikh");
$medecin->setSpecialite("Dentiste");
$medecin->setRoles(['ROLE_MEDECIN']);
$medecin->setPassword($this->hasher->hashPassword($medecin, 'passer123'));
$manager->persist($medecin);

// === Consultation ===
$consultation = new Consultation();
$consultation->setDate(new \DateTime());
$consultation->setConstantes([
'température' => '37.2',
'tension' => '12.8'
]);
$consultation->setStatut('validée');
$consultation->setPatient($patient);
$consultation->setMedecin($medecin);
$manager->persist($consultation);

// === Prestation ===
$prestation = new Prestation();
$prestation->setDate(new \DateTime());
$prestation->setType("Analyse");
$prestation->setStatut("validée");
$prestation->setResultats("Analyse du sang : normale");
$prestation->setPatient($patient);
$manager->persist($prestation);

// === RendezVous ===
$rv = new RendezVous();
$rv->setDate((new \DateTime())->modify('+2 days'));
$rv->setType("consultation");
$rv->setStatut("validé");
$rv->setPatient($patient);
$rv->setMedecin($medecin);
$manager->persist($rv);

$manager->flush();
}
}

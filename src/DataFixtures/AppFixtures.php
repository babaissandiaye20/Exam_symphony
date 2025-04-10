<?php
// src/DataFixtures/AppFixtures.php

namespace App\DataFixtures;

use App\Entity\Patient;
use App\Entity\RendezVous;
use App\Entity\Consultation;
use App\Entity\Prestation;
use App\Entity\Medecin;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public const PATIENTS_COUNT = 5;
    public const MEDECINS_COUNT = 3;
    
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Création des médecins
        for ($i = 0; $i < self::MEDECINS_COUNT; $i++) {
            $medecin = new Medecin();
            $medecin->setNom($faker->lastName);
            $medecin->setPrenom($faker->firstName);
            $medecin->setSpecialite($faker->word);
            $medecin->setEmail($faker->email);
            $medecin->setPassword($this->hasher->hashPassword($medecin, 'password123'));
            $medecin->setRoles(['ROLE_MEDECIN']);
            $manager->persist($medecin);
            $this->addReference('medecin_' . $i, $medecin);
        }

        // Création des patients
        for ($i = 0; $i < self::PATIENTS_COUNT; $i++) {
            $patient = new Patient();
            $patient->setNom($faker->lastName);
            $patient->setPrenom($faker->firstName);
            $patient->setEmail($faker->email);
            $patient->setPassword($this->hasher->hashPassword($patient, 'password123'));
            $patient->setCode("PAT" . ($i + 1));
            $patient->setAntecedentsMedicaux([$faker->word, $faker->word]);
            $patient->setRoles(['ROLE_PATIENT']);
            $manager->persist($patient);
            $this->addReference('patient_' . $i, $patient);
        }

        // Création des prestations
        for ($i = 0; $i < 10; $i++) {
            $prestation = new Prestation();
            $prestation->setType($faker->word);
            $prestation->setDate($faker->dateTimeBetween('now', '+1 month'));
            $prestation->setStatut('active');
            $prestation->setPatient($this->getReference('patient_' . rand(0, self::PATIENTS_COUNT - 1),Patient::class));
            $manager->persist($prestation);
        }

        // Création des consultations
        for ($i = 0; $i < 10; $i++) {
            $consultation = new Consultation();
            $consultation->setDate($faker->dateTimeBetween('now', '+1 month'));
            $consultation->setConstantes([
                'temperature' => rand(36, 39),
                'tension' => rand(60, 120),
            ]);
            $consultation->setStatut('active');
            $consultation->setPatient($this->getReference('patient_' . rand(0, self::PATIENTS_COUNT - 1),Patient::class));
            $consultation->setMedecin($this->getReference('medecin_' . rand(0, self::MEDECINS_COUNT - 1),Medecin::class));
            $manager->persist($consultation);
        }

        // Création des rendez-vous
        for ($i = 0; $i < 10; $i++) {
            $rendezVous = new RendezVous();
            $rendezVous->setDate($faker->dateTimeBetween('now', '+1 month'));
            $rendezVous->setType($faker->randomElement(['consultation', 'prestation']));
            $rendezVous->setStatut('active');
            $rendezVous->setPatient($this->getReference('patient_' . rand(0, self::PATIENTS_COUNT - 1),Patient::class));
            $rendezVous->setMedecin($this->getReference('medecin_' . rand(0, self::MEDECINS_COUNT - 1),Medecin::class));
            $manager->persist($rendezVous);
        }

        $manager->flush();
    }
}

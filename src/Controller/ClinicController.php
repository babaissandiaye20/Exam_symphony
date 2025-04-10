<?php
// src/Controller/ClinicController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClinicController extends AbstractController
{
    #[Route('/clinic/dashboard', name: 'app_clinic_dashboard')]
    public function dashboard(): Response
    {
        // Vous pourriez passer des données supplémentaires si nécessaire
        return $this->render('clinic/dashboard.html.twig', [
            'userName' => 'Sidy',
        ]);
    }
    
    #[Route('/clinic/appointments', name: 'app_clinic_appointments')]
    public function appointments(): Response
    {
        return $this->render('clinic/dashboard.html.twig', [
            'userName' => 'Sidy',
            // Données des rendez-vous
        ]);
    }
    
    // Ajoutez d'autres méthodes pour les différentes fonctionnalités
    // (consultations, prescriptions, demandes de rendez-vous)
}
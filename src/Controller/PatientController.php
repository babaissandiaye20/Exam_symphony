<?php

namespace App\Controller;

use App\Entity\RendezVous;
use App\Entity\Consultation;
use App\Entity\Prestation;
use App\Form\DemandeRendezVousType;
use App\Repository\RendezVousRepository;
use App\Repository\ConsultationRepository;
use App\Repository\PrestationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Core\Security;

#[Route('/patient')]
#[IsGranted('ROLE_PATIENT')]
class PatientController extends AbstractController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/dashboard', name: 'patient_dashboard', methods: ['GET'])]
    public function dashboard(RendezVousRepository $rvRepo, ConsultationRepository $consRepo, PrestationRepository $presRepo): Response
    {
        $patient = $this->security->getUser();

        if (!$patient) {
            throw $this->createAccessDeniedException('Vous devez être connecté.');
        }

        $rendezVous = $rvRepo
            ->findBy(['patient' => $patient]);

        $consultations = $consRepo
            ->findBy(['patient' => $patient]);

        $prestations = $presRepo
            ->findBy(['patient' => $patient]);

        return $this->render('patient/dashboard.html.twig', [
            'rendezVous' => $rendezVous,
            'consultations' => $consultations,
            'prestations' => $prestations,
        ]);
    }
    #[Route('/demande-rendez-vous', name: 'patient_demande_rendezvous', methods: ['GET', 'POST'])]
    public function demandeRendezVous(Request $request, EntityManagerInterface $entityManager): Response
    {
        $rendezVous = new RendezVous();
        $rendezVous->setPatient($this->getUser());
        $rendezVous->setStatut('En attente');
        
        $form = $this->createForm(DemandeRendezVousType::class, $rendezVous);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            if ($rendezVous->getType() === 'prestation' && $rendezVous->getPrestation() === null) {
                $this->addFlash('error', 'Veuillez sélectionner une prestation.');
                return $this->render('patient/demande_rendezvous.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
            
            $entityManager->persist($rendezVous);
            $entityManager->flush();
            
            $this->addFlash('success', 'Votre demande de rendez-vous a été enregistrée. Vous serez contacté pour confirmation.');
            
            return $this->redirectToRoute('patient_list_rendezvous');
        }
        
        return $this->render('patient/demande_rendezvous.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/rendezvous', name: 'patient_list_rendezvous', methods: ['GET'])]
    public function listRendezVous(RendezVousRepository $repo): Response
    {
        $patient = $this->getUser();
        $rdvs = $repo->findBy(['patient' => $patient]);

        return $this->render('patient/rendezvous.html.twig', [
            'rendezvous' => $rdvs,
        ]);
    }

    #[Route('/consultations', name: 'patient_list_consultations', methods: ['GET'])]
    public function listConsultations(ConsultationRepository $repo): Response
    {
        $patient = $this->getUser();
        $consultations = $repo->findBy(['patient' => $patient]);

        return $this->render('patient/consultations.html.twig', [
            'consultations' => $consultations,
        ]);
    }

    #[Route('/prestations', name: 'patient_list_prestations', methods: ['GET'])]
    public function listPrestations(PrestationRepository $repo): Response
    {
        $patient = $this->getUser();
        $prestations = $repo->findBy(['patient' => $patient]);

        return $this->render('patient/prestations.html.twig', [
            'prestations' => $prestations,
        ]);
    }

    #[Route('/consultation/{id}/annuler', name: 'patient_annuler_consultation', methods: ['POST'])]
    public function annulerConsultation(Consultation $consultation, EntityManagerInterface $em): Response
    {
        $now = new \DateTimeImmutable();
        $delai = $consultation->getDate()->diff($now);

        if ($delai->days < 2) {
            $this->addFlash('warning', 'Impossible d\'annuler moins de 48h à l\'avance.');
        } else {
            $em->remove($consultation);
            $em->flush();
            $this->addFlash('success', 'Consultation annulée.');
        }

        return $this->redirectToRoute('patient_list_consultations');
    }

    #[Route('/prestation/{id}/annuler', name: 'patient_annuler_prestation', methods: ['POST'])]
    public function annulerPrestation(Prestation $prestation, EntityManagerInterface $em): Response
    {
        $now = new \DateTimeImmutable();
        $delai = $prestation->getDate()->diff($now);

        if ($delai->days < 2) {
            $this->addFlash('warning', 'Impossible d\'annuler moins de 48h à l\'avance.');
        } else {
            $em->remove($prestation);
            $em->flush();
            $this->addFlash('success', 'Prestation annulée.');
        }

        return $this->redirectToRoute('patient_list_prestations');
    }

#[Route('/rendezvous/{id}/annuler', name: 'patient_annuler_rendezvous', methods: ['POST'])]
public function annulerRendezVous(RendezVous $rendezVous, EntityManagerInterface $em): Response
{
    $now = new \DateTimeImmutable();
    $delai = $rendezVous->getDate()->diff($now);

    if ($delai->days < 2) {
        $this->addFlash('warning', 'Impossible d\'annuler moins de 48h à l\'avance.');
    } else {
        $em->remove($rendezVous);
        $em->flush();
        $this->addFlash('success', 'Rendez-vous annulé.');
    }

    return $this->redirectToRoute('patient_list_rendezvous');
}

}

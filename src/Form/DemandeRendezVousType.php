<?php
// src/Form/DemandeRendezVousType.php
namespace App\Form;

use App\Entity\RendezVous;
use App\Entity\Medecin;
use App\Entity\Prestation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DemandeRendezVousType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateTimeType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'attr' => ['min' => (new \DateTime())->format('Y-m-d\TH:i')],
                'label' => 'Date souhaitée'
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Consultation' => 'consultation',
                    'Prestation' => 'prestation'
                ],
                'label' => 'Type de rendez-vous',
                'required' => true
            ])
            ->add('medecin', EntityType::class, [
                'class' => Medecin::class,
                'choice_label' => function (Medecin $medecin) {
                    return $medecin->getNom() . ' ' . $medecin->getPrenom();
                },
                'label' => 'Médecin (optionnel)',
                'placeholder' => 'Sélectionnez un médecin (facultatif)',
                'required' => false
            ])
            ->add('prestation', EntityType::class, [
                'class' => Prestation::class,
                'choice_label' => function (Prestation $prestation) {
                    return $prestation->getType();
                },
                'label' => 'Prestation (si applicable)',
                'placeholder' => 'Sélectionnez une prestation',
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RendezVous::class,
        ]);
    }
}
<?php

namespace App\Form;

use App\Entity\Employe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use App\Entity\FonctionEmploye;

class EmployeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Nom'
            ])
            ->add('Prenom', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Prénom'
            ])
            ->add('cin', NumberType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'CIN'
            ])
            ->add('salaire', NumberType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Salaire (DT)',
                'required' => false
            ])
            ->add('montant_cnss', NumberType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Montant CNSS (DT)'
            ])
            ->add('Couverture_sociale', CheckboxType::class, [
                'label' => 'Couverture sociale',
                'required' => false,
                'attr' => ['class' => 'form-check-input']
            ])
            ->add('telephone', NumberType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Téléphone',
                'required' => false
            ])
            ->add('fonctionEmploye', EntityType::class, [
    'class' => FonctionEmploye::class,
    'choice_label' => 'libelle',
    'label' => 'Fonction',
    'placeholder' => 'Sélectionnez une fonction',
    'attr' => ['class' => 'form-select']
])
->add('date_debut', DateType::class, [
    'widget' => 'single_text',
    'label' => 'Date de début',
    'attr' => ['class' => 'form-control']
])
->add('date_fin', DateType::class, [
    'widget' => 'single_text',
    'label' => 'Date de fin',
    'attr' => ['class' => 'form-control']
]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employe::class,
        ]);
    }
}
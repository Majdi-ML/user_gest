<?php

namespace App\Form;

use App\Entity\Bureau;
use App\Entity\StatusBureau;
use App\Entity\FonctionBureau;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class BureauType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Nom'
            ])
            ->add('prenom', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Prénom'
            ])
            ->add('telephone', NumberType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Téléphone'
            ])
            ->add('status', EntityType::class, [
                'class' => StatusBureau::class,
                'choice_label' => 'libelle',
                'attr' => ['class' => 'form-select'],
                'label' => 'Statut'
            ])
            ->add('fonction', EntityType::class, [
                'class' => FonctionBureau::class,
                'choice_label' => 'libelle',
                'attr' => ['class' => 'form-select'],
                'label' => 'Fonction'
            ])
            ->add('annee_debut', ChoiceType::class, [
            'label' => 'Année de début mondat',
            'placeholder' => 'Sélectionnez une année',
            'choices' => array_combine(array_reverse(range(date('Y'), 2000)), array_reverse(range(date('Y'), 2000))),
            'attr' => ['class' => 'form-select']
            ])
            ->add('annee_fin', ChoiceType::class, [
            'label' => 'Année de fin de mandat',
            'placeholder' => 'Sélectionnez une année',    
            'choices' => array_combine(array_reverse(range(date('Y'), 2000)), array_reverse(range(date('Y'), 2000))),
            'attr' => ['class' => 'form-select']
        ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bureau::class,
        ]);
    }
}
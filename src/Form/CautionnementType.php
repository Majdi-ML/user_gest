<?php

namespace App\Form;

use App\Entity\Cautionnement;
use App\Entity\Appartement;
use App\Entity\NaturePaiement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CautionnementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Montant', EntityType::class, [
                'class' => \App\Entity\FraisSyndic::class,
                'choice_label' => fn($frais) => $frais->getMontant() . ' TND',
                'label' => 'Montant',
                'placeholder' => 'Sélectionnez un montant',
                'attr' => ['class' => 'form-select']
            ])
            ->add('annee', ChoiceType::class, [
                'label' => 'Année',
                'choices' => array_combine(range(2000, 2035), range(2000, 2035)),
                'attr' => ['class' => 'form-select']
            ])
            ->add('Mois', ChoiceType::class, [
                'label' => 'Mois',
                'choices' => [
                    'Janvier' => 'Janvier',
                    'Février' => 'Février',
                    'Mars' => 'Mars',
                    'Avril' => 'Avril',
                    'Mai' => 'Mai',
                    'Juin' => 'Juin',
                    'Juillet' => 'Juillet',
                    'Août' => 'Août',
                    'Septembre' => 'Septembre',
                    'Octobre' => 'Octobre',
                    'Novembre' => 'Novembre',
                    'Décembre' => 'Décembre',
                ],
                'multiple' => true,
                'expanded' => false,
                'mapped' => false,
                'attr' => ['class' => 'form-select']
            ])
            ->add('appartement', EntityType::class, [
                'class' => Appartement::class,
                'choice_label' => fn(Appartement $a) => (string) $a,
                'placeholder' => 'Sélectionnez les appartements',
                'multiple' => true,
                'expanded' => false,
                'mapped' => false,
                'attr' => ['class' => 'form-select appartement-select']
            ])
            ->add('Nature_Paiement', EntityType::class, [
                'class' => NaturePaiement::class,
                'choice_label' => 'nature',
                'label' => 'Nature du paiement',
                'attr' => ['class' => 'form-select'],
                'placeholder' => 'Sélectionnez une nature de paiement',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cautionnement::class,
        ]);
    }
}
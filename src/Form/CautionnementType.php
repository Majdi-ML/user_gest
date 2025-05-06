<?php

// src/Form/CautionnementType.php

namespace App\Form;

use App\Entity\Cautionnement;
use App\Entity\Personne;
use App\Entity\Appartement;
use App\Entity\NaturePaiement; // Ajoutez cette ligne
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class CautionnementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montant', NumberType::class, [
                'label' => 'Montant',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Entrez le montant']
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
                'attr' => ['class' => 'form-select']
            ])
            ->add('Personne', EntityType::class, [
                'class' => Personne::class,
                'choice_label' => fn(Personne $p) => $p->getNom() . ' ' . $p->getPrenom(),
                'label' => 'Personne',
                'attr' => ['class' => 'form-select personne-select']
            ])
            ->add('appartement', EntityType::class, [
                'class' => Appartement::class,
                'choice_label' => fn(Appartement $a) => (string) $a,
                'placeholder' => 'Sélectionnez une personne',
                'attr' => ['class' => 'form-select appartement-select'],
                'query_builder' => fn(EntityRepository $er) => $er->createQueryBuilder('a'),
            ])
            // Ajoutez ce nouveau champ
            ->add('Nature_Paiement', EntityType::class, [
                'class' => NaturePaiement::class,
                'choice_label' => 'nature', // Supposons que NaturePaiement a une propriété 'libelle'
                'label' => 'Nature du paiement',
                'attr' => ['class' => 'form-select'],
                'placeholder' => 'Sélectionnez une nature de paiement',
                'required' => false, // Si le champ peut être null
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cautionnement::class,
        ]);
    }
}
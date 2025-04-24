<?php

namespace App\Form;

use App\Entity\Appartement;
use App\Entity\Bloc;
use App\Entity\Personne;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;


class AppartementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $proprietaires = $options['proprietaires'];
        $locataires = $options['locataires'];
        $builder
            ->add('numero', TextType::class, [
                'label' => 'Numéro',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Ex: A12']
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type',
                'choices' => [
                    'Studio' => 'studio',
                    'T1' => 't1',
                    'T2' => 't2',
                    'T3' => 't3',
                    'Duplex' => 'duplex',
                ],
                'attr' => ['class' => 'form-select']
            ])
            ->add('etage', IntegerType::class, [
                'label' => 'Étage',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Ex: 2']
            ])
            ->add('est_habite', CheckboxType::class, [
                'label' => 'Est habité ?',
                'required' => false,
                'attr' => ['class' => 'form-check-input']
            ])
            ->add('locataire', EntityType::class, [
                'class' => Personne::class,
                'choices' => $locataires,
                'choice_label' => fn($p) => $p->getNom() . ' ' . $p->getPrenom(),
                'label' => 'Locataire',
                'required' => false,
                'placeholder' => 'Choisir un locataire',
                'attr' => ['class' => 'form-select']
            ])
            ->add('proprietaire', EntityType::class, [
                'class' => Personne::class,
                'choices' => $proprietaires,
                'choice_label' => fn($p) => $p->getNom() . ' ' . $p->getPrenom(),
                'label' => 'Propriétaire',
                'placeholder' => 'Choisir un propriétaire',
                'attr' => ['class' => 'form-select']
            ])
            ->add('bloc', EntityType::class, [
                'class' => Bloc::class,
                'choice_label' => 'nom',
                'label' => 'Bloc',
                'attr' => ['class' => 'form-select']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appartement::class,
            'proprietaires' => [],
            'locataires' => [],
        ]);
    }
}

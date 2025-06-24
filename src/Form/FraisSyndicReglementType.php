<?php

namespace App\Form;

use App\Entity\FraisSyndicReglement;
use App\Entity\Appartement;
use App\Entity\FraisSyndic;
use App\Entity\NaturePaiement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FraisSyndicReglementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('frais', EntityType::class, [
                'class' => FraisSyndic::class,
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
            ->add('Janvier', CheckboxType::class, ['label' => 'Janvier', 'required' => false])
            ->add('Fevrier', CheckboxType::class, ['label' => 'Février', 'required' => false])
            ->add('Mars', CheckboxType::class, ['label' => 'Mars', 'required' => false])
            ->add('Avril', CheckboxType::class, ['label' => 'Avril', 'required' => false])
            ->add('Mai', CheckboxType::class, ['label' => 'Mai', 'required' => false])
            ->add('Juin', CheckboxType::class, ['label' => 'Juin', 'required' => false])
            ->add('Juillet', CheckboxType::class, ['label' => 'Juillet', 'required' => false])
            ->add('Aout', CheckboxType::class, ['label' => 'Août', 'required' => false])
            ->add('Septembre', CheckboxType::class, ['label' => 'Septembre', 'required' => false])
            ->add('Octobre', CheckboxType::class, ['label' => 'Octobre', 'required' => false])
            ->add('Novembre', CheckboxType::class, ['label' => 'Novembre', 'required' => false])
            ->add('Decembre', CheckboxType::class, ['label' => 'Décembre', 'required' => false])
            ->add('appartement', EntityType::class, [
                'class' => Appartement::class,
                'choice_label' => fn(Appartement $a) => (string) $a,
                'multiple' => true,
                'mapped' => false,
                'expanded' => false,
                'attr' => ['class' => 'form-select multiselect appartement-select']
            ])
            ->add('nature_paiement', EntityType::class, [
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
            'data_class' => FraisSyndicReglement::class,
        ]);
    }
}
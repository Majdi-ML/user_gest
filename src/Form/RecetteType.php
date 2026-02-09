<?php

namespace App\Form;

use App\Entity\Recette;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Vich\UploaderBundle\Form\Type\VichFileType;

class RecetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montant', null, [
                'label' => 'Montant (DT)',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('description', null, [
                'label' => 'Description',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('type_recette', ChoiceType::class, [
                'label' => 'Type de recette',
                'choices'  => [
                    'Cotisation Syndic' => 'Cotisation Syndic',
                    'Autre revenu' => 'Autre revenu',
                ],
                'placeholder' => 'Sélectionner un type',
                'attr' => ['class' => 'form-select'],
            ])
            ->add('nature_recette', ChoiceType::class, [
                'label' => 'Nature de recette',
                'choices'  => [
                    'Espèce' => 'Espece',
                    'Bancaire' => 'Bancaire',
                ],
                'placeholder' => 'Sélectionner une nature',
                'attr' => ['class' => 'form-select'],
            ])
            ->add('date_recette', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de recette',
                'attr' => ['class' => 'form-control']
            ])
            ->add('file', VichFileType::class, [
                'label' => 'Fichier joint (PDF/Image)',
                'required' => false,
                'allow_delete' => true,
                'download_uri' => true,
                'attr' => ['class' => 'form-control'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
        ]);
    }
}

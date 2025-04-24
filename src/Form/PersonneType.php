<?php

namespace App\Form;

use App\Entity\Personne;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Nom'],
            ])
            ->add('prenom', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Prénom'],
            ])
            ->add('email', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Email'],
            ])
            ->add('telephone', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Téléphone'],
            ])
            ->add('adresse', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Adresse'],
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Propriétaire' => 'proprietaire',
                    'Locataire' => 'locataire',
                ],
                'attr' => ['class' => 'form-select'],
                'placeholder' => 'Sélectionnez un statut',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Personne::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Cautionnement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CautionnementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montant')
            ->add('date_paiement')
            ->add('Personne_id')
            ->add('appartement_id')
            ->add('user_id')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cautionnement::class,
        ]);
    }
}

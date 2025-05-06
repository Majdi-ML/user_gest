<?php

namespace App\Form;

use App\Entity\Rassemeblement;
use App\Entity\TypeRassmblement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Vich\UploaderBundle\Form\Type\VichFileType;

class RassemeblementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'label' => 'Date du rassemblement'
            ])
            ->add('Type', EntityType::class, [
                'class' => TypeRassmblement::class,
                'choice_label' => 'libelle',
                'attr' => ['class' => 'form-select'],
                'label' => 'Type de rassemblement',
                'placeholder' => 'Sélectionnez un type'
            ])
            ->add('pvFile', VichFileType::class, [
                'label' => 'PV du rassemblement (PDF/Image)',
                'required' => false,
                'allow_delete' => true,
                'download_uri' => true,
                'attr' => ['class' => 'form-control'],
                'delete_label' => 'Supprimer le fichier',
                'download_label' => 'Télécharger'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rassemeblement::class,
        ]);
    }
}
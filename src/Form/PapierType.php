<?php

namespace App\Form;

use App\Entity\Papier;
use App\Entity\TypePapier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Vich\UploaderBundle\Form\Type\VichFileType;

class PapierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 3,
                    'placeholder' => 'Description du document'
                ],
                'label' => 'Description'
            ])
            ->add('date_creation', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'label' => 'Date de création'
            ])
            ->add('typePapier', EntityType::class, [
                'class' => TypePapier::class,
                'choice_label' => 'libelle',
                'attr' => ['class' => 'form-select'],
                'label' => 'Type de document',
                'placeholder' => 'Sélectionnez un type'
            ])
            ->add('documentFile', VichFileType::class, [
                'label' => 'Document (PDF/Image)',
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
            'data_class' => Papier::class,
        ]);
    }
}
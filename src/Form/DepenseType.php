<?php

namespace App\Form;

use App\Entity\Depense;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Vich\UploaderBundle\Form\Type\VichFileType;
class DepenseType extends AbstractType
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
            ->add('type', ChoiceType::class, [
                'label' => 'Type de dépense',
                'choices'  => [
                    'SALAIRE' => 'SALAIRE',
                    'CNSS' => 'CNSS',
                    'STEG' => 'STEG',
                    'SONEDE' => 'SONEDE',
                    'IMPOTS' => 'IMPOTS',
                    'JARDINAGE' => 'JARDINAGE',
                    'PRODUITS D\'ENTRETIEN' => 'PRODUITS_ENTRETIEN',
                    'GROS ŒUVRES & ENTRETIEN' => 'GROS_ŒUVRES_ENTRETIEN',
                    'FRAIS JURIDIQUE' => 'FRAIS_JURIDIQUE',
                    'DIVERS' => 'DIVERS',
                ],
                'placeholder' => 'Sélectionner un type',
                'attr' => ['class' => 'form-select'],
            ])
            ->add('date_depense', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de dépense',
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
            'data_class' => Depense::class,
        ]);
    }
}

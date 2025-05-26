<?php
namespace App\Form;

use App\Entity\Cnss;
use App\Entity\Bureau;
use App\Repository\BureauRepository; // Import the correct repository
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Vich\UploaderBundle\Form\Type\VichFileType;

class CnssType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $annees = range(date('Y'), date('Y') - 10);
        $annees = array_combine($annees, $annees);

        $builder
            ->add('trimstre', ChoiceType::class, [
                'choices' => [
                    '1er trimestre' => 1,
                    '2ème trimestre' => 2,
                    '3ème trimestre' => 3,
                    '4ème trimestre' => 4
                ],
                'attr' => ['class' => 'form-select'],
                'label' => 'Trimestre',
                'placeholder' => 'Sélectionnez un trimestre'
            ])
            ->add('annee', ChoiceType::class, [
                'choices' => $annees,
                'label' => 'Année',
                'placeholder' => 'Sélectionnez une année',
                'attr' => ['class' => 'form-select']
            ])
            ->add('montant_totale', NumberType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Montant total (DT)'
            ])
            ->add('bureau', EntityType::class, [
                'class' => Bureau::class,
                'query_builder' => function (BureauRepository $er) { // Change EntityRepository to BureauRepository
                    return $er->createQueryBuilder('b')
                        ->join('b.status', 's')
                        ->where('s.libelle = :active')
                        ->setParameter('active', 'actif');
                },
                'choice_label' => function ($bureau) {
                    return $bureau->getNom() . ' ' . $bureau->getPrenom();
                },
                'attr' => ['class' => 'form-select'],
                'label' => 'Responsable',
                'placeholder' => 'Sélectionnez un responsable'
            ])
            ->add('cnssFile', VichFileType::class, [
                'label' => 'Déclaration CNSS (PDF/Image)',
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
            'data_class' => Cnss::class,
        ]);
    }
}
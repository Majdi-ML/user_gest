<?php

namespace App\Form;

use App\Entity\Bureau;
use App\Entity\StatusBureau;
use App\Entity\FonctionBureau;
use App\Entity\Personne;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use App\Repository\PersonneRepository;

class BureauType extends AbstractType
{
    private $personneRepository;

    public function __construct(PersonneRepository $personneRepository)
    {
        $this->personneRepository = $personneRepository;

    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
         $currentYear = (int) date('Y');
    $years = range($currentYear, $currentYear + 10);
        $builder
            ->add('personne', EntityType::class, [
                'class' => Personne::class,
                'choice_label' => function (Personne $personne) {
                    return $personne->getNom() . ' ' . $personne->getPrenom();
                },
                'label' => 'Propriétaire',
                'placeholder' => 'Sélectionnez un propriétaire',
                'attr' => ['class' => 'form-select'],
                'choices' => $this->personneRepository->findProprietaires(),
                'mapped' => false,
            ])
            ->add('nom', HiddenType::class, [
                'required' => false,
            ])
            ->add('prenom', HiddenType::class, [
                'required' => false,
            ])
            ->add('telephone', HiddenType::class, [
                'required' => false,
            ])
            ->add('status', EntityType::class, [
                'class' => StatusBureau::class,
                'choice_label' => 'libelle',
                'attr' => ['class' => 'form-select'],
                'label' => 'Statut'
            ])
            ->add('fonction', EntityType::class, [
                'class' => FonctionBureau::class,
                'choice_label' => 'libelle',
                'attr' => ['class' => 'form-select'],
                'label' => 'Fonction'
            ])
            ->add('annee_debut', ChoiceType::class, [
                'label' => 'Année de début mandat',
                'placeholder' => 'Sélectionnez une année',
                'choices' => array_combine(range(2000, 2035), range(2000, 2035)),
                'attr' => ['class' => 'form-select']
            ])
            ->add('annee_fin', ChoiceType::class, [
                'label' => 'Année de fin de mandat',
                'placeholder' => 'Sélectionnez une année',
                'choices' => array_combine($years, $years),
                'attr' => ['class' => 'form-select']
            ]);

        // Add event listener to copy personne data to nom, prenom, telephone
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $bureau = $event->getData();
            $personne = $form->get('personne')->getData();

            if ($personne instanceof Personne) {
                $bureau->setNom($personne->getNom());
                $bureau->setPrenom($personne->getPrenom());
                $bureau->setTelephone($personne->getTelephone());
            }

            $event->setData($bureau);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bureau::class,
        ]);
    }
}
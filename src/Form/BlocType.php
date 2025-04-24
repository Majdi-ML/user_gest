<?php

namespace App\Form;

use App\Entity\Bloc;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlocType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Nom du bloc'],
                'label' => 'Nom'
            ])
            ->add('nombre_etages', IntegerType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Nombre d\'étages'],
                'label' => 'Nombre des étages'
            ])
            ->add('nombre_appartement_etage', IntegerType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Appartements par étage'],
                'label' => 'Nombres des appartements par étage'
            ])
            ->add('nombre_totale_appartement', IntegerType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Total des appartements'],
                'label' => 'Nombre total des appartements'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bloc::class,
        ]);
    }
}

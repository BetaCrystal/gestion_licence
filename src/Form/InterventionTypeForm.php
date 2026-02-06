<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\InterventionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InterventionTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'label' => 'Nom - champ obligatoire',
            'required' => true,
        ])
        ->add('description', TextType::class, [
            'label' => 'Description - champ obligatoire',
            'required' => true,
        ])
        ->add('color', TextType::class, [
            'label' => 'Code couleur (hexadécimal) - champ obligatoire',
            'required' => true,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InterventionType::class,
        ]);
    }
}
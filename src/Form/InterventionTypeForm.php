<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\InterventionType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class InterventionTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'label' => 'Nom - champ obligatoire',
            'required' => true,
            'attr' => ['class' => 'border border-slate-300 rounded-md px-3 py-1'],
            'constraints' => [
                    new NotBlank(message: 'Le nom est obligatoire.'),
                ]
        ])
        ->add('description', TextType::class, [
            'label' => 'Description - champ obligatoire',
            'required' => true,
            'attr' => ['class' => 'border border-slate-300 rounded-md px-3 py-1'],
            'constraints' => [
                    new NotBlank(message: 'La description est obligatoire.'),
                ]
        ])
        ->add('color', TextType::class, [
            'label' => 'Code couleur (hexadécimal) - champ obligatoire',
            'required' => true,
            'attr' => ['class' => 'border border-slate-300 rounded-md px-3 py-1'],
            'constraints' => [
                    new NotBlank(message: 'La couleur est obligatoire.'),
                ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InterventionType::class,
        ]);
    }
}
<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\TeachingBlock;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Module;
use Symfony\Component\Validator\Constraints\NotBlank;

class ModuleForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('teachingBlock', EntityType::class, [
            'class' => TeachingBlock::class,
            'disabled' => true,
            'label' => 'Bloc enseignement',
            'choice_label' => 'name',
            'attr' => ['class' => 'border border-slate-300 rounded-md px-3 py-1'],
            'constraints' => [
                    new NotBlank(message: 'Le bloc d\'enseignement est obligatoire.'),
                ]
        ])
        ->add('code', TextType::class, [
            'label' => 'Code - champ obligatoire',
            'required' => true,
            'attr' => ['class' => 'border border-slate-300 rounded-md px-3 py-1'],
            'constraints' => [
                    new NotBlank(message: 'Le code est obligatoire.'),
                ]
        ])
        ->add('name', TextType::class, [
            'label' => 'Nom - champ obligatoire',
            'required' => true,
            'attr' => ['class' => 'border border-slate-300 rounded-md px-3 py-1'],
            'constraints' => [
                    new NotBlank(message: 'Le nom est obligatoire.'),
                ]
        ])
        ->add('hours_count', NumberType::class, [
            'label' => 'Nombre d\'heures',
            'required' => false,
            'attr' => ['class' => 'border border-slate-300 rounded-md px-3 py-1'],
        ])
        ->add('parent', EntityType::class, [
            'class' => Module::class,
            'choice_label' => 'name',
            'label' => 'Module parent',
            'placeholder' => 'Sélectionnez le module parent',
            'required' => false,
            'attr' => ['class' => 'border border-slate-300 rounded-md px-3 py-1'],
        ])
        ->add('description', TextareaType::class, [
            'label' => 'Description - champ obligatoire',
            'required' => true,
            'attr' => ['class' => 'border border-slate-300 rounded-md px-3 py-1'],
            'constraints' => [
                    new NotBlank(message: 'La description est obligatoire.'),
                ]
        ])
        ->add('capstone_project', CheckboxType::class, [
            'label' => 'Module effectué sur le projet fil rouge',
            'required' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}

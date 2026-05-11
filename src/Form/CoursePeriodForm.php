<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use App\Entity\CoursePeriod;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\SchoolYear;
use Symfony\Component\Validator\Constraints\NotBlank;

final class CoursePeriodForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de début - champ obligatoire',
                'required' => true,
                'attr' => ['class' => 'border border-slate-300 rounded-md px-3 py-1'],
                'constraints' => [
                    new NotBlank(message: 'La date de début est obligatoire.'),
                ]
            ])
            ->add('endDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de fin - champ obligatoire',
                'required' => true,
                'attr' => ['class' => 'border border-slate-300 rounded-md px-3 py-1'],
                'constraints' => [
                    new NotBlank(message: 'La date de fin est obligatoire.'),
                ]
            ])
            ->add('schoolYear', EntityType::class, [
                'class' => SchoolYear::class,
                'choice_label' => 'name',
                'label' => 'Année scolaire associée - champ obligatoire',
                'placeholder' => 'Sélectionnez une année scolaire',
                'required' => true,
                'disabled' => true,
                'attr' => ['class' => 'border border-slate-300 rounded-md px-3 py-1'],
                'constraints' => [
                    new NotBlank(message: 'L\'année scolaire est obligatoire.'),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CoursePeriod::class,
        ]);
    }
}
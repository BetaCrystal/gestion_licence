<?php

namespace App\Form;

use App\Entity\Instructor;
use App\Entity\InterventionType;
use App\Entity\Module;
use App\Entity\CoursePeriod;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

final class CourseForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'required' => false,
                'attr' => ['class' => 'border border-slate-300 rounded-md px-3 py-1 mb-5 mt-10'],
            ])
            ->add('startDate', DateTimeType::class, [
                'label' => 'Date de début - champ obligatoire',
                'required' => true,
                'attr' => ['class' => 'border border-slate-300 rounded-md px-3 py-1 mb-5 mr-5'],
                'constraints' => [
                    new NotBlank(message: 'La date de début est obligatoire.'),
                ]
            ])
            ->add('endDate', DateTimeType::class, [
                'label' => 'Date de fin - champ obligatoire',
                'required' => true,
                'attr' => ['class' => 'border border-slate-300 rounded-md px-3 py-1 mb-5'],
                'constraints' => [
                    new NotBlank(message: 'La date de fin est obligatoire.'),
                ]
            ])
            ->add('module', EntityType::class, [
                'class' => Module::class,
                'label' => 'Module - champ obligatoire',
                'required' => true,
                'choice_label' => 'name',
                'attr' => ['class' => 'border border-slate-300 rounded-md px-3 py-1 mb-5 mr-5'],
                'constraints' => [
                    new NotBlank(message: 'Le module est obligatoire.'),
                ]
            ])
            ->add('interventionType', EntityType::class, [
                'class' => InterventionType::class,
                'label' => 'Type d\'intervention - champ obligatoire',
                'required' => true,
                'choice_label' => 'name',
                'attr' => ['class' => 'border border-slate-300 rounded-md px-3 py-1 mb-5'],
                'constraints' => [
                    new NotBlank(message: 'Le type d\'intervention est obligatoire.'),
                ]
            ])
            ->add('CourseInstructor', EntityType::class, [
                'class' => Instructor::class,
                'label' => 'Intervenants - champ obligatoire',
                'required' => true,
                'multiple' => true,
                'choice_label' => 'user.lastName',
                'expanded' => true,
                'attr' => ['class' => 'mb-5'],
                'constraints' => [
                    new NotBlank(message: 'Au moins un intervenant est obligatoire.'),
                ]
            ])
            /*->add('coursePeriodId', EntityType::class, [
                'class' => CoursePeriod::class,
                'label' => 'Période du cours - champ obligatoire',
                'required' => true,
                'choice_label' => function (CoursePeriod $period) {
                    return $period->getStartDate()->format('d/m/Y') . ' - ' . $period->getEndDate()->format('d/m/Y');
                },
            ])*/
            ->add('remotely', CheckboxType::class, [
                'label' => 'Intervention effectuée en visio',
                'required' => false,
                'attr' => ['class' => 'mb-10'],
            ]);
    }
}

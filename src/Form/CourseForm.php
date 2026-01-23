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

final class CourseForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'required' => false,
            ])
            ->add('startDate', DateTimeType::class, [
                'label' => 'Date de début - champ obligatoire',
                'required' => true,
            ])
            ->add('endDate', DateTimeType::class, [
                'label' => 'Date de fin - champ obligatoire',
                'required' => true,
            ])
            ->add('module', EntityType::class, [
                'class' => Module::class,
                'label' => 'Module - champ obligatoire',
                'required' => true,
                'choice_label' => 'name',
            ])
            ->add('interventionType', EntityType::class, [
                'class' => InterventionType::class,
                'label' => 'Type d\'intervention - champ obligatoire',
                'required' => true,
                'choice_label' => 'name',
            ])
            ->add('CourseInstructor', EntityType::class, [
                'class' => Instructor::class,
                'label' => 'Intervenants - champ obligatoire',
                'required' => true,
                'multiple' => true,
                'choice_label' => 'user_id.lastName',
                'expanded' => true,
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
            ]);
    }
}
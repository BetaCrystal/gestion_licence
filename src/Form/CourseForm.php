<?php

namespace App\Form;

use App\Entity\Instructor;
use App\Entity\InterventionType;
use App\Entity\Module;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
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
            ->add('start_date', DateType::class, [
                'label' => 'Date de début - champ obligatoire',
                'required' => true,
            ])
            ->add('end_date', DateType::class, [
                'label' => 'Date de fin - champ obligatoire',
                'required' => true,
            ])
            ->add('module', EntityType::class, [
                'class' => Module::class,
                'label' => 'Module - champ obligatoire',
                'required' => true,
            ])
            ->add('intervention_type', EntityType::class, [
                'class' => InterventionType::class,
                'label' => 'Type d\'intervention - champ obligatoire',
                'required' => true,
            ])
            ->add('instructor', EntityType::class, [
                'class' => Instructor::class,
                'label' => 'Intervenants - champ obligatoire',
                'required' => true,
            ])
            ->add('remote', CheckboxType::class, [
                'label' => 'Intervention effectuée en visio',
                'required' => true,
            ]);
    }
}
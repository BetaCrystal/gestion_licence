<?php

namespace App\Form;

use App\Entity\Course;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\TypeInfo\Type\ObjectType;

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
            ->add('module', ObjectType::class, [
                'label' => 'Module - champ obligatoire',
                'required' => true,
            ])
            ->add('intervention_type', ObjectType::class, [
                'label' => 'Type d\'intervention - champ obligatoire',
                'required' => true,
            ])
            ->add('instructor', ObjectType::class, [
                'label' => 'Intervenants - champ obligatoire',
                'required' => true,
            ])
            ->add('remote', BooleanType::class, [
                'label' => 'Intervention effectuée en visio',
                'required' => true,
            ]);
    }
}
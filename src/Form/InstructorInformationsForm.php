<?php

namespace App\Form;

use App\Entity\Instructor;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use App\Entity\ModuleInstructor;
use App\Entity\Module;

final class InstructorInformationsForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastName', TextType::class, [
                'label' => 'Nom de famille - champ obligatoire',
                'required' => false,
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom- champ obligatoire',
                'required' => false,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email - champ obligatoire',
                'required' => false,
            ])
            ->add('module', EntityType::class, [
                'class' => Module::class,
                'label' => 'Module - champ obligatoire',
                'required' => true,
                'choice_label' => 'name',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer les informations',
            ]);
    }
}
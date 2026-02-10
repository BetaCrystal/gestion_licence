<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Module;
use Symfony\Component\Validator\Constraints\NotBlank;

class InterventionForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('DateDebut', DateTimeType::class, [
                'label' => 'Date de début',
                'required' => false,
                'attr' => ['class' => 'border border-slate-300 rounded-md px-3 py-1'],
        ])
        ->add('DateFin', DateTimeType::class, [
                'label' => 'Date de fin',
                'required' => false,
                'attr' => ['class' => 'border border-slate-300 rounded-md px-3 py-1'],
        ])
        ->add('Module', EntityType::class, [
            'class' => Module::class,
            'choice_label' => 'name',
            'label' => 'Module',
            'placeholder' => 'Sélectionnez le module',
            'required' => false,
            'attr' => ['class' => 'border border-slate-300 rounded-md px-3 py-1'],
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Filtrer',
            'attr' => ['class' => 'px-4 py-2.5 text-blue-950 bg-yellow-500 font-medium text-base rounded-xl'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}

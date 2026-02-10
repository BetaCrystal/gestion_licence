<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\InterventionType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class InterventionTypeFilterForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'label' => 'Nom',
            'attr' => ['class' => 'border border-slate-300 rounded-md px-3 py-1'],
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Filtrer',
            'attr' => ['class' => 'px-4 py-2.5 text-blue-950 bg-yellow-500 font-medium text-base rounded-xl'],
        ]);
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['csrf_protection'=> false]);
    }
}
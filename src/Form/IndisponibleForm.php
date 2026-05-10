<?php

namespace App\Form;

use App\Entity\Indisponible;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Instructor;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class IndisponibleForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('instructor', EntityType::class, [
                'class' => Instructor::class,
                'required' => true,
                'disabled' => true,
            ])
            ->add('start_date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de début - champ obligatoire',
                'required' => true,
                'attr' => ['class' => 'border border-slate-300 rounded-md px-3 py-1'],
                'constraints' => [
                    new NotBlank(message: 'La date de début est obligatoire.'),
                ] ])
            ->add('end_date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de fin - champ obligatoire',
                'required' => true,
                'attr' => ['class' => 'border border-slate-300 rounded-md px-3 py-1'],
                'constraints' => [
                    new NotBlank(message: 'La date de fin est obligatoire.'),
                ]
            ])
            ->add('motif', TextType::class, [
                'label' => 'Motif',
                'required' => 'false',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Indisponible::class,
        ]);
    }
}

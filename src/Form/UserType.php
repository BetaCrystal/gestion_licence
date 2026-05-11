<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastName', TextType::class, [
                'label' => 'Nom de famille',
                'attr' => ['class' => 'border border-slate-300 rounded-md px-3 py-1'],
                'constraints' => [
                    new NotBlank(message: 'Le nom de famille est obligatoire.'),
                    new Regex(
                        pattern: '/^[a-zA-Z]+$/',
                        message: 'Ce champ ne doit contenir que des lettres.'
                    ),
                    new Length(
                        max: 255,
                        maxMessage: 'Le nom de famille ne doit pas dépasser {{ limit }} caractères.'
                    )
                ]
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['class' => 'border border-slate-300 rounded-md px-3 py-1'],
                'constraints' => [
                    new NotBlank(message: 'Le prénom est obligatoire.'),
                    new Regex(
                        pattern: '/^[a-zA-Z]+$/',
                        message: 'Ce champ ne doit contenir que des lettres.'
                    ),
                    new Length(
                        max: 255,
                        maxMessage: 'Le nom de famille ne doit pas dépasser {{ limit }} caractères.'
                    )
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['class' => 'border border-slate-300 rounded-md px-3 py-1'],
                'constraints' => [
                    new NotBlank(message: 'L\'email est obligatoire.'),
                    new Length(
                        max: 255,
                        maxMessage: 'L\'email ne doit pas dépasser {{ limit }} caractères.'
                    )
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

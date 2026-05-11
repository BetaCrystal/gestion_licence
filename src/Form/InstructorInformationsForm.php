<?php

namespace App\Form;

use App\Entity\Instructor;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\ModuleInstructor;
use App\Entity\Module;
use App\Form\UserType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Constraints\Count;

final class InstructorInformationsForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user', UserType::class)
            ->add('Module', EntityType::class, [
                'class' => Module::class,
                'multiple' => true,
                'choice_label' => 'name',
                'expanded' => true,
                'by_reference' => false,
                'constraints' => [
                    new Count(
                        min: 1,
                        minMessage: 'Vous devez sélectionner au moins un module.'
                    ),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer les informations',
                'attr' => ['class' => 'px-4 py-2.5 text-white bg-blue-950 font-medium text-base rounded-xl mt-3'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }

}
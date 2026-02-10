<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\TeachingBlock;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Validator\Constraints\NotBlank;

class TeachingBlockForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('code', TextType::class, [
                'label' => 'Code du bloc - champ obligatoire',
                'required' => false,
                'disabled' => true,
                'attr' => ['class' => 'border border-slate-300 rounded-md px-3 py-1'],
            ])
        ->add('name', TextType::class, [
                'label' => 'Nom du bloc - champ obligatoire',
                'required' => false,
                'disabled' => true,
                'attr' => ['class' => 'border border-slate-300 rounded-md px-3 py-1'],
            ])
        ->add('hoursCount', NumberType::class, [
                'label' => 'Nombre d\'heures - champ obligatoire',
                'required' => false,
                'attr' => [
                    'min' => 0,
                    'step' => 1,
                    'class' => 'border border-slate-300 rounded-md px-3 py-1',
                ],
                'constraints' => [
                    new NotBlank(message: 'Le nombre d\'heures est obligatoire.'),
                ]
            ])
        ->add('description', TextareaType::class, [
                'label' => 'Description - champ obligatoire',
                'required' => false,
                'constraints' => [
                    new NotBlank(message: 'La description est obligatoire.'),
                ],
                'attr' => ['class' => 'border border-slate-300 rounded-md px-3 py-1'],
            ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TeachingBlock::class,
        ]);
    }
}
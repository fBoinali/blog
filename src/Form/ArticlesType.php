<?php

namespace App\Form;

use App\Entity\Team;
use App\Entity\Articles;
use App\Entity\Categories;
//use Doctrine\DBAL\Types\DateTimeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ArticlesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre',
                'required' => true
            ])
            ->add('article', TextType::class, [
                'label' => 'Article',
                'required' => true
            ])

            // ->add('date', DateType::class, [
            //     // 'widget' => 'single_text',
            //     'label' => 'Date de l\'article',
            //     'required' => true
            // ])

            ->add('fk_team', EntityType::class, [
                'class' => Team::class,
                'label' => 'Posté par',
                'required' => true
            ])

            ->add('fk_categories', EntityType::class, [
                'class' => Categories::class,
                'label' => 'Catégorie',
                'required' => true
            ])

            ->add('logo', FileType::class, [
                'data_class'=> null,
                'label' => 'Logo',
                'required' => false,
                'help' => 'Fichier jpg, jpeg, png, ou webp ne depassant pas 1Mo',
                'constraints' => [
                    new File([
                        'maxSize'=> '10M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp'
                        ],
                        'mimeTypesMessage' => "This document isn't valid",
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Articles::class,
        ]);
    }
}

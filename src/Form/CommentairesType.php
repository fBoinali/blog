<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Articles;
use App\Entity\Commentaires;
//use Doctrine\DBAL\Types\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CommentairesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('commentaire', TextType::class, [
                'label' => 'commentaire',
                'required' => true
            ])

            // ->add('date', DateType::class, [
            //     'widget' => 'single_text',
            //     'required' => true
            // ])

            ->add(
                'fk_article',
                EntityType::class,
                [
                    'class' => Articles::class,
                    'label' => 'Titre de l\'article',
                    'required' => true
                ]
            )

            ->add('fk_user', EntityType::class, [
                'class' => User::class,
                'label' => 'Commenté par',
                'required' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaires::class,
        ]);
    }
}

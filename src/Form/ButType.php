<?php

namespace App\Form;

use App\Entity\But;
use App\Entity\Joueur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ButType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('joueur', EntityType::class, [
                'class' => Joueur::class,
                'choices' => $options['joueurs'], // Utiliser la liste des joueurs du match
                'choice_label' => function ($joueur) {
                    return $joueur->getEquipe()->getNom().' - '.$joueur->getNom() . ' (N°' . $joueur->getNumero() . ')';
                },
            ])
            ->add('minute', IntegerType::class)
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Normal' => 'normal',
                    'Penalty' => 'penalty',
                    'CSC (Contre son camp)' => 'csc',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => But::class,
            'joueurs' => [], // On attend les joueurs comme option
        ]);
    }
}
